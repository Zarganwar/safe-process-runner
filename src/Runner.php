<?php

namespace Zarganwar\SafeProcessRunner;


use Exception;
use RuntimeException;

class Runner
{
	/** @var string */
	private $lockPath;

	/** @param string $lockPath */
	public function __construct($lockPath)
	{
		$this->lockPath = $lockPath;
	}

	/**
	 * @param string $id
	 * @param callable $callable
	 * @return bool
	 * @throws Exception
	 */
	public function runCallable($id, callable $callable)
	{
		if (!file_exists($this->lockPath) && !mkdir($this->lockPath, 0777, true) && !is_dir($this->lockPath)) {
			throw new RuntimeException(sprintf('Directory `%s` was not created', $this->lockPath));
		}

		$resource = fopen($this->lockPath . '/' . $id . '.lock', 'w+');
		if (!flock($resource, LOCK_EX | LOCK_NB)) {  // acquire an exclusive lock
			fclose($resource);
			return false;
		}

		try {
			call_user_func($callable);
		} catch (Exception $e) {
			throw $e;
		} finally {
			flock($resource, LOCK_UN);
			unlink($this->lockPath . '/' . $id . '.lock');
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