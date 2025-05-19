<?php

namespace App\Helpers;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GalleryHelper
{
    /**
     * Tambahkan gambar ke galeri entitas
     */
    public static function addImage(Model $entity, UploadedFile $file, string $caption = null, bool $isFeatured = false, int $order = 0): Gallery
    {
        $path = $file->store('gallery', 'public');

        return Gallery::create([
            'imageable_id' => $entity->id,
            'imageable_type' => get_class($entity),
            'file_path' => $path,
            'caption' => $caption,
            'is_featured' => $isFeatured,
            'order' => $order,
        ]);
    }

    /**
     * Hapus gambar dari galeri
     */
    public static function removeImage(Gallery $gallery): bool
    {
        if (Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        return $gallery->delete();
    }

    /**
     * Dapatkan gambar unggulan entitas
     */
    public static function getFeaturedImage(Model $entity): ?Gallery
    {
        return $entity->galleries()
            ->where('is_featured', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Dapatkan semua gambar entitas
     */
    public static function getImages(Model $entity, bool $includeFeatured = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = $entity->galleries();

        if (!$includeFeatured) {
            $query->where('is_featured', false);
        }

        return $query->orderBy('order')->get();
    }
}
