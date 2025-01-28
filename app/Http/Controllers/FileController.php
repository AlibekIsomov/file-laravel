<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    private static $allowedPaths = ['allowed/path1', 'allowed/path2'];
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function putObject(Request $request)
    {
        Log::info('Request data:', $request->all());

        $validated = $request->validate([
            'path' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:1024',
            'fileName' => 'required|string|regex:/^[a-zA-Z0-9]+$/'
        ]);

        Log::info('Validated data:', $validated);

        try {
            $file = $this->fileService->uploadFile(
                self::$allowedPaths,
                $validated['path'],
                $request->file('file'),
                $validated['fileName']
            );
            return response()->json(['path' => $file], 201);
        } catch (\Exception $e) {
            Log::error('Exception:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getObject(Request $request)
    {
        Log::info('Request data:', $request->all());
        $validated = $request->validate([
            'filePath' => 'required|string'
        ], [], $request->query()); // Validate query parameters for GET request

        try {
            $file = $this->fileService->getFile($validated['filePath']);
            return response()->json(['file' => $file]);
        } catch (\Exception $e) {
            Log::error('Exception:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function postObject(Request $request)
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
}