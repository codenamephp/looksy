<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData;

/**
 * Interface for collecting the raw data of a php run. The rawData returned is streamable and can then be saved and/or processed further
 *
 * An example implementation would be to start an xdebug trace
 */
interface iCollector {

  /**
   * Starts the data collection
   *
   * @throws DataCollectionFailedException
   */
  public function start() : void;

  /**
   * Stops the data collection and returns the raw data
   *
   * @return iRawData
   * @throws DataCollectionFailedException
   */
  public function stop() : iRawData;
}