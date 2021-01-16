<?php declare(strict_types=1);

namespace de\codenamephp\looksy\rawData\persistance;

use de\codenamephp\looksy\rawData\FromResource;
use de\codenamephp\looksy\rawData\iRawData;
use DirectoryIterator;

/**
 * Saves and loads raw data from and to files in a folder
 */
final class Folder implements iPersistnace {

  /**
   * @param string $folder The folder the raw data is persistet to
   */
  public function __construct(public string $folder) { }

  public function save(iRawData ...$rawData) : void {
    $this->createFolder();

    foreach($rawData as $rawDatum) {
      $originalFilename = basename(stream_get_meta_data($rawDatum->getStream())['uri']);
      $targetStream = fopen(implode(DIRECTORY_SEPARATOR, [$this->folder, $originalFilename]), 'wb');
      stream_copy_to_stream($rawDatum->getStream(), $targetStream);
      fclose($targetStream);
      rewind($rawDatum->getStream());
    }
  }

  public function load() : array {
    $iterator = new DirectoryIterator($this->folder);

    $rawData = [];
    foreach($iterator as $item) {
      if($item->isFile()) $rawData[] = new FromResource(fopen($item->getPathname(), 'rb'));
    }
    return $rawData;
  }

  private function createFolder() : void {
    is_dir($this->folder) || @mkdir(directory: $this->folder, recursive: true) || throw new SavingFailedException('Could not create folder ' . $this->folder);
  }
}