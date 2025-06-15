<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UpdateService
{
    // Fetch all updates with the first paragraph
    public function getUpdates($tenant_id)
    {
        // Subquery to get the first paragraph number for each update
        $firstParagraphs = DB::table('update_details as ud')
            ->selectRaw('ud.update_id, MIN(ud.paragraph_no) as min_paragraph_no')
            ->groupBy('ud.update_id');

        return DB::table('updates as u')
            ->leftJoinSub(
                DB::table('update_details as ud')
                    ->joinSub($firstParagraphs, 'fp', function ($join) {
                        $join->on('ud.update_id', '=', 'fp.update_id')
                            ->whereColumn('ud.paragraph_no', '=', 'fp.min_paragraph_no');
                    })
                    ->select('ud.update_id', 'ud.paragraph_text','ud.detail_image'),
                'ud',
                'u.id',
                '=',
                'ud.update_id'
            )
            ->where('u.tenant_id', $tenant_id)
            ->select('u.*', 'ud.paragraph_text as first_paragraph', 'ud.detail_image')
            ->get();
    }
   
    
    // Fetch a specific update and all its paragraphs
    public function getUpdateDetail($id, $tenant_id)
    {
        $result = DB::table('updates')
            ->leftJoin('update_details', 'updates.id', '=', 'update_details.update_id')
            ->where('updates.tenant_id', $tenant_id)
            ->where('updates.id', $id)
            ->orderBy('update_details.paragraph_no', 'asc')
            ->select(
                'updates.*',
                'update_details.paragraph_text',
                'update_details.detail_image'
            )
            ->get();

        if ($result->isEmpty()) {
            return ['update' => null, 'paragraphs' => []];
        }

        // Extract update details
        $update = $result->first();
        $paragraphs = $result->pluck('paragraph_text')->toArray();

        return [
            'update' => $update,
            'paragraphs' => $paragraphs
        ];
    }
}
