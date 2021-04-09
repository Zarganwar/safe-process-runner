<?php

namespace Zarganwar\SafeProcessRunner;


class Runner
{
	/** @var string */
	private $lockPath;

	public function __construct(string $lockPath)
	{
		$this->lockPath = $lockPath;
	}

	public function runCallable(string $id, callable $callable)
	{
		if (!file_exists($this->lockPath) && !mkdir($this->lockPath, 0777, true) && !is_dir($this->lockPath)) {
			throw new \RuntimeException(sprintf('Directory `%s` was not created', $this->lockPath));
		}

		$lock = $this->lockPath . '/' . md5($id) . '.lock';
		$resource = fopen($lock, 'w+');
		if (!flock($resource, LOCK_EX | LOCK_NB)) {  // acquire an exclusive lock
			fclose($resource);
			return false;
		}

		try {
			call_user_func($callable);
		} catch (\Throwable $e) {
			throw $e;
		} finally {
			flock($resource, LOCK_UN);
			unlink($lock);
			fclose($resource);
		}

		return true;
	}

	public function run(string $id, IProcess $process)
	{
		return $this->runCallable($id, function () use ($process) {
			$process->run();
		});
	}
}
