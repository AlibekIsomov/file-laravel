<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private static $allowedPaths = ['uploads/allowed/path1', 'uploads/allowed/path2'];
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function putObject(Request $request)
    {
        Log::info('putObject method called');
        Log::info('Request method: ' . $request->method());
        Log::info('Request data:', $request->all());
        Log::info('Files in request:', $request->allFiles());

        try {
            $validator = Validator::make($request->all(), [
                'path' => 'required|string',
                'file' => 'required|file|mimes:pdf|max:1024',
                'fileName' => 'required|string|regex:/^[a-zA-Z0-9]+$/'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            Log::info('Validation passed', $validated);

            $file = $this->fileService->putObject(
                self::$allowedPaths,
                $validated['path'],
                $request->file('file'),
                $validated['fileName']
            );

            Log::info('File uploaded successfully', ['path' => $file]);
            return response()->json(['path' => $file], 201);
        } catch (\Exception $e) {
            Log::error('Exception in putObject:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'File upload failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getObject(Request $request)
    {
        Log::info('getObject method called');
        Log::info('Request data:', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'filePath' => 'required|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            Log::info('Validation passed', $validated);

            $fileContent = $this->fileService->getObject($validated['filePath']);
            
            Log::info('File retrieved successfully');
            return response($fileContent, 200)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            Log::error('Exception in getObject:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'File retrieval failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function postObject(Request $request)
    {
        dd($request->all(), $request->file());
        $validated = $request->validate([
            'path' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:1024',
            'fileName' => 'required|string|regex:/^[a-zA-Z0-9]+$/'
        ]);

        try {
            $file = $this->fileService->putObject(
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