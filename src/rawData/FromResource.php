<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData;

/**
 * Just a container for an already existing resource
 */
final class FromResource implements iRawData {

  /**
   * @param resource $resource
   */
  public function __construct(public $resource) {}

  public function getStream() {
    return $this->resource;
  }
}