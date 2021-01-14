<?php declare(strict_types=1);

namespace de\codenamephp\looksy\test\rawData;

use de\codenamephp\looksy\rawData\FromResource;
use PHPUnit\Framework\TestCase;

class FromResourceTest extends TestCase {

  private FromResource $sut;

  protected function setUp() : void {
    $this->sut = new FromResource(fopen(__DIR__, 'r'));
  }

  public function testGetStream() : void {
    self::assertSame($this->sut->resource, $this->sut->getStream());
  }
}
