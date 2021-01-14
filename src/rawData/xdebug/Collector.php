<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData\xdebug;

use de\codenamephp\looksy\rawData\DataCollectionFailedException;
use de\codenamephp\looksy\rawData\FromResource;
use de\codenamephp\looksy\rawData\iCollector;
use de\codenamephp\looksy\rawData\iRawData;

/**
 * Collects data using xdebug_(start|stop)_trace functions. The rawData created is a file pointer to the file xdebug creates
 */
final class Collector implements iCollector {

  /**
   * Contains the xdebug trace file that was created last (the return value of xdebug_start_trace). If no trace was started this will be null
   *
   * @var string|null
   */
  public ?string $lastTraceFilename = null;

  /**
   * @param string|null $traceFileName The file name the trace will be saved to as accepted by xdebug_start_trace
   * @param int|null $options The options to start the trace with as accepted by xdebug_start_trace
   *
   * @see https://xdebug.org/docs/all_functions#xdebug_start_trace
   */
  public function __construct(public ?string $traceFileName = null, public ?int $options = null) { }

  /**
   * @inheritDoc
   * @see https://xdebug.org/docs/all_functions#xdebug_start_trace
   */
  public function start() : void {
    $this->lastTraceFilename = xdebug_start_trace($this->traceFileName, $this->options);
  }

  /**
   * @inheritDoc
   * @see https://xdebug.org/docs/all_functions#xdebug_stop_trace
   * @throws DataCollectionFailedException when the file that was returned by xdebug_stop_trace is not readable (or not a file at all)
   */
  public function stop() : iRawData {
    $fileName = (string) xdebug_stop_trace();

    return is_readable($fileName) ? new FromResource(fopen($fileName, 'rb')) : throw new DataCollectionFailedException('No path to file received, cannot create raw data.');
  }
}