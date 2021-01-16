<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData\xdebug;

use de\codenamephp\looksy\test\rawData\xdebug\CollectorTest;
use PHPUnit\Framework\TestCase;

function xdebug_start_trace(?string $filename = null, ?int $options = null) : ?string {
  TestCase::assertEquals(CollectorTest::$start_expectedFilename, $filename);
  TestCase::assertEquals(CollectorTest::$start_expectedOptions, $options);
  return CollectorTest::$start_expectedReturnFilename;
}

function xdebug_stop_trace() : string|false {
  return CollectorTest::$stop_expectedFilename;
}

namespace de\codenamephp\looksy\test\rawData\xdebug;

use de\codenamephp\looksy\rawData\DataCollectionFailedException;
use de\codenamephp\looksy\rawData\xdebug\Collector;
use PHPUnit\Framework\TestCase;

final class CollectorTest extends TestCase {

  public static ?string $start_expectedFilename = null;

  public static ?string $start_expectedReturnFilename = null;

  public static ?int $start_expectedOptions = null;

  public static string|false $stop_expectedFilename = false;

  private Collector $sut;

  protected function setUp() : void {
    $this->sut = new Collector();

    self::$start_expectedFilename = null;
    self::$start_expectedReturnFilename = null;
    self::$start_expectedOptions = XDEBUG_TRACE_COMPUTERIZED;
    self::$stop_expectedFilename = false;
  }

  public function testStart() : void {
    self::$start_expectedReturnFilename = 'some return file';

    $this->sut->start();

    self::assertEquals('some return file', $this->sut->lastTraceFilename);
  }

  public function testStart_withFilenameAndOptions() : void {
    self::$start_expectedFilename = $this->sut->traceFileName = 'some file';
    self::$start_expectedOptions = $this->sut->options = 123;

    $this->sut->start();
  }

  public function testStop() : void {
    self::$stop_expectedFilename = __FILE__;

    $rawData = $this->sut->stop();

    self::assertIsResource($rawData->getStream());

    $metaData = stream_get_meta_data($rawData->getStream());
    self::assertEquals(__FILE__, $metaData['uri']);
    self::assertEquals('rb', $metaData['mode']);
  }

  public function testStop_canThrowException_ifReturnedFileIsNotReadable() : void {
    self::$stop_expectedFilename = false;
    $this->expectException(DataCollectionFailedException::class);
    $this->expectDeprecationMessage('No path to file received, cannot create raw data.');

    $this->sut->stop();
  }

}
