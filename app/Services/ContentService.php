<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ContentService
{
    public function getContents($tenantId)
    {
        return DB::table('content')
            ->where('tenant_id', $tenantId)
            ->get()
            ->toArray();
    }

    public function getContentDetail($id, $tenantId)
    {
        $content = DB::table('content')
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (!$content) {
            return ['content' => null, 'paragraphs' => []];
        }

        $contentDetails = DB::table('content_details')
            ->where('content_id', $id)
            ->orderBy('paragraph_order', 'asc')
            ->pluck('paragraph_text')
            ->toArray();

        return [
            'content' => $content,
            'paragraphs' => $contentDetails
        ];
    }
}
