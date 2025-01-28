<?php

namespace Tests\Unit\Repositories;

use PHPUnit\Framework\TestCase;
use App\Repositories\FileRepository;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileRepositoryTest extends TestCase
{
    private $repository;
    private $storageMock;
    private $fileMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->storageMock = $this->getMockBuilder(Storage::class)
            ->disableOriginalConstructor()
            ->addMethods(['put'])
            ->getMock();

        $this->fileMock = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->addMethods(['create'])
            ->getMock();

        $this->repository = new FileRepository($this->storageMock, $this->fileMock);
    }

    public function testStoresFileSuccessfully()
    {
        // Arrange
        $file = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $path = 'test/path';
        $fileName = 'test.pdf';
        $fullPath = "{$path}/{$fileName}";

        $file->expects($this->once())
            ->method('get')
            ->willReturn('file contents');

        $this->storageMock->expects($this->once())
            ->method('put')
            ->with($fullPath, 'file contents')
            ->willReturn(true);

        $this->fileMock->expects($this->once())
            ->method('create')
            ->with([
                'filename' => $fileName,
                'path' => $path
            ])
            ->willReturn(new File());

        // Act
        $result = $this->repository->putObject($path, $file, $fileName);

        // Assert
        $this->assertEquals($fullPath, $result);
    }
}