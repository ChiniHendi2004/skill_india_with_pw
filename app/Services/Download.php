<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Download extends Model
{
    use HasFactory;

    protected $table = 'downloads'; // Primary table

    public static function fetchDownloads($tenantId)
    {
        return DB::table('downloads')
            ->join('download_groups', 'downloads.group_id', '=', 'download_groups.id')
            ->leftJoin('download_details', 'downloads.id', '=', 'download_details.download_id')
            ->select(
                'downloads.id',
                'downloads.title',
                'downloads.file_path',
                'downloads.tenant_id',
                'download_groups.group_name',
                'download_details.description'
            )
            ->where('downloads.tenant_id', $tenantId)
            ->get();
    }

    public static function fetchDownloadById($id, $tenantId)
    {
        return DB::table('downloads')
            ->join('download_groups', 'downloads.group_id', '=', 'download_groups.id')
            ->leftJoin('download_details', 'downloads.id', '=', 'download_details.download_id')
            ->select(
                'downloads.id',
                'downloads.title',
                'downloads.file_path',
                'downloads.tenant_id',
                'download_groups.group_name',
                'download_details.description'
            )
            ->where('downloads.tenant_id', $tenantId)
            ->where('downloads.id', $id)
            ->first();
    }
}
