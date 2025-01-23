<?php
namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $fileService;
    private static $allowedPaths = ['allowed/path1', 'allowed/path2'];
    private static $provider;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
        self::$provider = app(\App\Providers\FileProviderInterface::class);
    }

    public function putObject(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:1024',
            'fileName' => 'required|string|regex:/^[a-zA-Z0-9]+$/'
        ]);

        try {
            $file = $this->fileService->uploadFile(
                self::$allowedPaths,
                $request->input('path'),
                $request->file('file'),
                $request->input('fileName')
            );
            return response()->json(['path' => $file], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getObject(Request $request)
    {
        $request->validate([
            'filePath' => 'required|string'
        ]);

        try {
            return response()->json(['file' => $this->fileService->getFile($request->input('filePath'))]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}