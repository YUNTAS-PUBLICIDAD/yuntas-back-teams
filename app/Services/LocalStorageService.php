<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class LocalStorageService
{
    protected $disk;
    protected $imagePath = 'imagenes'; // Subdirectorio dentro de storage/app/public

    public function __construct()
    {
        $this->disk = Storage::disk('public');
    }

    public function uploadImage(UploadedFile $file): ?string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->store('imagenes', 'public');

        if ($path) {
            // Retorna la URL pública completa
            return 'storage/' . $path;
        }

        return null;
    }

    public function deleteImage(string $url): bool
    {
        // Extrae la ruta relativa desde la URL pública
        // Ejemplo: de http://localhost/storage/imagenes/image.jpg a imagenes/image.jpg
        $parsedUrl = parse_url($url);
        $path = Str::after(isset($parsedUrl['path']) ? $parsedUrl['path'] : '', '/storage/');

        if ($this->disk->exists($path)) {
            return $this->disk->delete($path);
        }

        return false;
    }

    
}