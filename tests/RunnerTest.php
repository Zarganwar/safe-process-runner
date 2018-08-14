<?php declare(strict_types=1);

namespace Tests\Zarganwar\SafeProcessRunner;

use Mockery;
//use Mockery\MockInterface;
use Tester\Assert;
use Tester\TestCase;
//use Zarganwar\SafeProcessRunner\IProcess;
//use Zarganwar\SafeProcessRunner\Runner;

require __DIR__ . '/bootstrap.php';

final class SchedulerTest extends TestCase
{

	protected function tearDown(): void
	{
		Mockery::close();
	}

	public function testRun(): void
	{
		Assert::true(true);

		// TODO
//		/** @var MockInterface|IProcess $processFailed */
//		$processFailed = Mockery::mock(IProcess::class)
//			->shouldReceive('run')
//			->andReturn(false)
//			->getMock();
//
//		/** @var MockInterface|IProcess $processSuccess */
//		$processSuccess = Mockery::mock(IProcess::class)
//			->shouldReceive('run')
//			->andReturn(true)
//
//			->getMock();
//
//		$scheduler = new Runner(__DIR__ . '/temp');
//		$scheduler->run('lock-id-1', $processSuccess);
//		$scheduler->run('lock-id-1', $processFailed);
	}
}

(new SchedulerTest())->run();