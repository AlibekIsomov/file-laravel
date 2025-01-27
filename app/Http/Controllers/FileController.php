<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /** @var FileService */
    private $fileService;
    private static $allowedPaths = ['allowed/path1', 'allowed/path2'];

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function putObject(Request $request)
    {
        $validated = $request->validate([
            'path' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:1024',
            'fileName' => 'required|string|regex:/^[a-zA-Z0-9]+$/'
        ]);

        try {
            $file = $this->fileService->uploadFile(
                self::$allowedPaths,
                $validated['path'],
                $request->file('file'),
                $validated['fileName']
            );
            return response()->json(['path' => $file], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getObject(Request $request)
    {
        $validated = $request->validate([
            'filePath' => 'required|string'
        ]);

        try {
            $file = $this->fileService->getFile($validated['filePath']);
            return response()->json(['file' => $file]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}