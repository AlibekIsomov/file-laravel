<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FileRepositoryTest extends TestCase
{
    
    /** @var FileRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->repository = new FileRepository();
    }

    /** @test */
    public function it_stores_file_successfully()
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $path = 'test/path';
        $fileName = 'test.pdf';

        $result = $this->repository->putObject($path, $file, $fileName);

        $this->assertEquals("{$path}/{$fileName}", $result);
        Storage::assertExists("{$path}/{$fileName}");
    }

    /** @test */
    public function it_throws_exception_when_file_not_found()
    {
        $this->expectException(RuntimeException::class);
        $this->repository->getObject('non/existent/path');
    }

    /** @test */
    public function it_retrieves_existing_file_content()
    {
        $content = 'test content';
        $path = 'test/existing.txt';
        Storage::put($path, $content);

        $result = $this->repository->getObject($path);

        $this->assertEquals($content, $result);
    }

    /** @test */
    public function it_throws_exception_when_storage_fails()
    {
        Storage::shouldReceive('put')->andReturn(false);
        
        $this->expectException(RuntimeException::class);
        
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $this->repository->putObject('test', $file, 'test.pdf');
    }
}