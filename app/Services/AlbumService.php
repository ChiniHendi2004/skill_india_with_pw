<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AlbumService
{
    public function getAllAlbums($tenantId)
    {
        return DB::table('albums')
            ->where('tenant_id', $tenantId)
            ->where('status', '1')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getAlbumDetails($id, $tenantId)
    {
        return [
            'album' => DB::table('albums')
                ->where('id', $id)
                ->where('tenant_id', $tenantId)
                ->where('status', '1')
                ->first(),
            'images' => DB::table('album_images')
                ->where('album_id', $id)
                ->where('status', '1')
                ->get(),
        ];
    }
    public function getLatestAlbum($tenantId)
    {
        return DB::table('album_images')
            ->where('tenant_id', $tenantId)
            ->where('status', '1')
            ->orderBy('created_at', 'asc')
            ->limit(9)
            ->get();
    }
}
