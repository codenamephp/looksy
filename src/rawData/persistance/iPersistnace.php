<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData\persistance;

use de\codenamephp\looksy\rawData\iRawData;

/**
 * Interface to persist raw data. Implementations must make sure the data persisted correctly and that the rsource pointers are rewinded
 */
interface iPersistnace {

  /**
   * Persists the data using the streams from the raw data and rewinds the the streams afterwards
   *
   * @param iRawData ...$rawData The data to be save
   * @throws SavingFailedException
   */
  public function save(iRawData ...$rawData) : void;

  /**
   * Loads the raw data and makes sure the streams are rewinded
   *
   * @return array<iRawData>
   * @throws LoadingFailedException
   */
  public function load() : array;
}