<?php declare(strict_types=1);

namespace de\codenamephp\looksy\test\rawData\persistance;

use de\codenamephp\looksy\rawData\persistance\Folder;
use PHPUnit\Framework\TestCase;

final class FolderTest extends TestCase {

  private Folder $sut;

  protected function setUp() : void {
    $this->sut = new Folder();
  }

  public function testSave() : void {
  }

  public function testLoad() : void {
  }
}
