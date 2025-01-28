<?php

namespace Tests\Feature\Controllers;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\FileController;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\MockObject\MockObject;

class FileControllerTest extends TestCase
{
    
    /** @var FileController */
    private $controller;
    /** @var MockObject */
    private $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var FileService&MockObject */
        $this->fileService = $this->createMock(FileService::class);
        $this->controller = new FileController($this->fileService);
    }

    public function testUploadFile(): void
    {
        $file = $this->createMock(UploadedFile::class);
        $request = Request::create('/post-object', 'POST', [], [], ['file' => $file]);
        
        $request->expects($this->once())
            ->method('file')
            ->with('file')
            ->willReturn($file);

        $this->fileService->expects($this->once())
            ->method('uploadFile')
            ->with('uploads', $file, 'test.pdf')
            ->willReturn('uploads/test.pdf');

        $response = $this->controller->upload($request);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'status' => 'success',
            'path' => 'uploads/test.pdf'
        ], json_decode($response->getContent(), true));
    }

    public function testPutObject(): void
    {
    $file = $this->createMock(UploadedFile::class);
    $request = Request::create('/put-object', 'PUT', [
        'path' => 'allowed/path1',
        'fileName' => 'testfile'
    ], [], ['file' => $file]);

    $request->expects($this->once())
        ->method('validate')
        ->willReturn([
            'path' => 'allowed/path1',
            'file' => $file,
            'fileName' => 'testfile'
        ]);

    $request->expects($this->once())
        ->method('file')
        ->with('file')
        ->willReturn($file);

    $this->fileService->expects($this->once())
        ->method('uploadFile')
        ->with(['allowed/path1', 'allowed/path2'], 'allowed/path1', $file, 'testfile')
        ->willReturn('allowed/path1/testfile.pdf');

    $response = $this->controller->putObject($request);

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertEquals(['path' => 'allowed/path1/testfile.pdf'], json_decode($response->getContent(), true));
}

    public function testGetObject(): void
    {
    $request = Request::create('/get-object', 'GET', ['filePath' => 'allowed/path1/testfile.pdf']);

    $request->expects($this->once())
        ->method('validate')
        ->willReturn([
            'filePath' => 'allowed/path1/testfile.pdf'
        ]);

    $this->fileService->expects($this->once())
        ->method('getFile')
        ->with('allowed/path1/testfile.pdf')
        ->willReturn('file content');

    $response = $this->controller->getObject($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals(['file' => 'file content'], json_decode($response->getContent(), true));
}

public function testPostObject(): void
{
    $file = $this->createMock(UploadedFile::class);
    $request = Request::create('/post-object', 'POST', [
        'path' => 'allowed/path1',
        'fileName' => 'testfile'
    ], [], ['file' => $file]);

    $request->expects($this->once())
        ->method('validate')
        ->willReturn([
            'path' => 'allowed/path1',
            'file' => $file,
            'fileName' => 'testfile'
        ]);

    $request->expects($this->once())
        ->method('file')
        ->with('file')
        ->willReturn($file);

    $this->fileService->expects($this->once())
        ->method('uploadFile')
        ->with(['allowed/path1', 'allowed/path2'], 'allowed/path1', $file, 'testfile')
        ->willReturn('allowed/path1/testfile.pdf');

    $response = $this->controller->postObject($request);

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertEquals(['path' => 'allowed/path1/testfile.pdf'], json_decode($response->getContent(), true));
}
}