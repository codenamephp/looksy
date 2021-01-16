<?php declare(strict_types=1);

namespace de\codenamephp\looksy\test\rawData\persistance;

use de\codenamephp\looksy\rawData\iRawData;
use de\codenamephp\looksy\rawData\persistance\Folder;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class FolderTest extends TestCase {

  private Folder $sut;

  protected function setUp() : void {
    $this->sut = new Folder('');

    vfsStream::setup();
  }

  protected function tearDown() : void {
    parent::tearDown();

    foreach(['file1', 'file2', 'file3'] as $file) { //since infection might run without folder the files might be created in root
      $filename = implode(DIRECTORY_SEPARATOR, [realpath(__DIR__ . '/../../../'), $file]);
      if(file_exists($filename)) unlink($filename);
    }
  }

  public function testSave() : void {
    vfsStream::create([
        'some' => [
            'source' => [
                'folder' => [
                    'file1' => 'file content 1',
                    'file2' => 'file content 2',
                    'file3' => 'file content 3',
                ],
            ],
        ],
    ]);

    $this->sut->folder = vfsStream::url('root/my/folder');

    $rawData1 = $this->createMock(iRawData::class);
    $rawData1->method('getStream')->willReturn(fopen(vfsStream::url('root/some/source/folder/file1'), 'rb'));
    $rawData2 = $this->createMock(iRawData::class);
    $rawData2->method('getStream')->willReturn(fopen(vfsStream::url('root/some/source/folder/file2'), 'rb'));
    $rawData3 = $this->createMock(iRawData::class);
    $rawData3->method('getStream')->willReturn(fopen(vfsStream::url('root/some/source/folder/file3'), 'rb'));

    $this->sut->save($rawData1, $rawData2, $rawData3);

    self::assertFileEquals(vfsStream::url('root/some/source/folder/file1'), vfsStream::url('root/my/folder/file1'));
    self::assertFileEquals(vfsStream::url('root/some/source/folder/file2'), vfsStream::url('root/my/folder/file2'));
    self::assertFileEquals(vfsStream::url('root/some/source/folder/file3'), vfsStream::url('root/my/folder/file3'));

    self::assertSame(0, ftell($rawData1->getStream()));
    self::assertSame(0, ftell($rawData2->getStream()));
    self::assertSame(0, ftell($rawData3->getStream()));
  }

  public function testLoad() : void {
    vfsStream::create([
        'some' => [
            'source' => [
                'folder' => [
                    'file1' => 'file content 1',
                    'file2' => 'file content 2',
                    'file3' => 'file content 3',
                ],
            ],
        ],
    ]);

    $this->sut->folder = vfsStream::url('root/some/source/folder');

    $rawData = $this->sut->load();

    self::assertEquals('file content 1', stream_get_contents($rawData[0]->getStream()));
    self::assertEquals('file content 2', stream_get_contents($rawData[1]->getStream()));
    self::assertEquals('file content 3', stream_get_contents($rawData[2]->getStream()));
  }
}
