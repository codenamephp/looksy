<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData;

/**
 * Represents the raw data collected for a run. Since function calls add up really fast we expect very large amounts of data so all raw data must be streamable
 * to avoid consuming huge amounts of memory.
 */
interface iRawData {

  /**
   * Gets the stream to resource, e.g. a file or a database stream
   *
   * @return resource
   */
  public function getStream();
}