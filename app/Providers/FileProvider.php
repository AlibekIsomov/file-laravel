<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;

class FileProvider implements FileProviderInterface
{
    public function putObject($tempPath, $path)
    {
        return Storage::move($tempPath, $path);
    }

    public function getObject($filePath)
    {
        return Storage::get($filePath);
    }
}