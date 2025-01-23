<?php

namespace App\Providers;

interface FileProviderInterface
{
    public function putObject($tempPath, $path);
    public function getObject($filePath);
}