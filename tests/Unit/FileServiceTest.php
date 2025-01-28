<?php

namespace Tests\Feature;

use App\Services\FileService;
use App\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\TestCase;

class FileServiceTest extends TestCase
{
    private $fileRepositoryMock;
    private $fileService;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var FileRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject $fileRepositoryMock */
        $this->fileRepositoryMock = $this->createMock(FileRepositoryInterface::class);
        $this->fileService = new FileService($this->fileRepositoryMock);
    }

    /** @test */
    public function it_should_upload_a_valid_file_successfully()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('testfile.txt', 500); // 500 KB
        $fileName = 'validFileName123';
        $path = 'uploads';

        $this->fileRepositoryMock->expects($this->once())
            ->method('save')
            ->with('temp/' . $fileName, $path);

        $tempPath = $this->fileService->uploadFile($path, $file, $fileName);

        Storage::disk('local')->exists('temp/' . $fileName);
        $this->assertEquals('temp/' . $fileName, $tempPath);
    }

    /** @test */
    public function it_should_throw_an_exception_if_file_size_exceeds_limit()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File size exceeds the limit');

        $file = UploadedFile::fake()->create('largefile.txt', 2000); // 2000 KB
        $fileName = 'validFileName123';
        $path = 'uploads';

        $this->fileService->uploadFile($path, $file, $fileName);
    }

    /** @test */
    public function it_should_throw_an_exception_if_file_name_is_invalid()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File name contains invalid characters');

        $file = UploadedFile::fake()->create('testfile.txt', 500); // 500 KB
        $fileName = 'invalid-file-name!';
        $path = 'uploads';

        $this->fileService->uploadFile($path, $file, $fileName);
    }

    /** @test */
    public function it_should_get_a_file_successfully()
    {
        Storage::fake('local');
        $filePath = 'uploads/testfile.txt';

        Storage::disk('local')->put($filePath, 'File content');

        $this->fileRepositoryMock->expects($this->once())
            ->method('get')
            ->with($filePath)
            ->willReturn('File content');

        $fileContent = $this->fileService->getFile($filePath);

        $this->assertEquals('File content', $fileContent);
    }
}
