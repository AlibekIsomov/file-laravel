<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class FileControllerTest extends TestCase
{
    protected $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->fileService = Mockery::mock(FileService::class);
        $this->app->instance(FileService::class, $this->fileService);
    }

    /** @test */
    public function it_uploads_file_successfully()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $this->fileService
            ->shouldReceive('uploadFile')
            ->once()
            ->andReturn('allowed/path1/document.pdf');

        $response = $this->postJson('/api/files', [
            'path' => 'allowed/path1',
            'file' => $file,
            'fileName' => 'document'
        ]);

        $response->assertStatus(201)
                ->assertJson(['path' => 'allowed/path1/document.pdf']);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/files', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['path', 'file', 'fileName']);
    }

    /** @test */
    public function it_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.jpg', 100);

        $response = $this->postJson('/api/files', [
            'path' => 'allowed/path1',
            'file' => $file,
            'fileName' => 'document'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_retrieves_file_successfully()
    {
        $this->fileService
            ->shouldReceive('getFile')
            ->with('allowed/path1/document.pdf')
            ->andReturn('file content');

        $response = $this->getJson('/api/files?filePath=allowed/path1/document.pdf');

        $response->assertStatus(200)
                ->assertJson(['file' => 'file content']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}