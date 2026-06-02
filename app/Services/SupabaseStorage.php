<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        $this->url    = rtrim(env('SUPABASE_URL', ''), '/');
        $this->key    = env('SUPABASE_KEY', '');
        $this->bucket = env('SUPABASE_BUCKET', 'GiftZone');
    }

    public function upload(UploadedFile $file, string $folder = 'produtos'): ?string
    {
        $ext      = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(8) . '.' . $ext;
        $path     = $folder . '/' . $filename;

        $mimeTypes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'png'  => 'image/png',
        ];

        $contentType = $mimeTypes[strtolower($ext)] ?? 'application/octet-stream';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type'  => $contentType,
        ])->withBody(
            file_get_contents($file->getRealPath()),
            $contentType
        )->post("{$this->url}/storage/v1/object/{$this->bucket}/{$path}");

        if ($response->successful()) {
            return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
        }

        \Log::error('Supabase upload failed', [
            'status' => $response->status(),
            'body'   => $response->body(),
            'path'   => $path,
        ]);

        return null;
    }

    public function delete(string $publicUrl): bool
    {
        $prefix = "{$this->url}/storage/v1/object/public/{$this->bucket}/";
        if (!str_starts_with($publicUrl, $prefix)) {
            return false;
        }

        $path = str_replace($prefix, '', $publicUrl);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
        ])->delete("{$this->url}/storage/v1/object/{$this->bucket}", [
            'prefixes' => [$path],
        ]);

        return $response->successful();
    }

    public static function isSupabaseUrl(string $url): bool
    {
        return str_contains($url, 'supabase.co/storage/');
    }
}