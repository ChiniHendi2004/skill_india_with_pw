<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Download extends Model
{

    public static function getDownloadGroups($tenantId)
    {
        return DB::table('download_groups')
        ->where('tenant_id', $tenantId)
            ->select('id', 'group_name')
            ->get();
    }

    // Fetch downloads based on group_id
    public static function getDownloads($tenantId, $groupId)
    {
        return DB::table('downloads')
        ->where('tenant_id', $tenantId)
            ->where('download_group_id', $groupId)
            ->select('id', 'title', 'file_path')
            ->get();
    }

    // Fetch a specific download detail
    public static function getDownloadDetail($id)
    {
        return DB::table('downloads as d')
            ->leftJoin('download_groups as g', 'd.download_group_id', '=', 'g.id')
            ->leftJoin('download_details as dd', 'd.id', '=', 'dd.download_id')
            ->where('d.id', $id)
            ->select('d.id', 'd.title', 'd.file_path', 'g.name as group_name', 'dd.paragraph_text')
            ->orderBy('dd.paragraph_order', 'asc') // Order by paragraph_order in ascending order
            ->get(); // Use `get()` instead of `first()` to return all paragraphs in order
    }

}
