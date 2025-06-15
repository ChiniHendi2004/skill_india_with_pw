<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CommitteeService
{
    public function getCommittees($tenantId)
    {
        return DB::table('committees')
            ->where('tenant_id', $tenantId)
            ->get()
            ->toArray();
    }

    public function getCommitteeDetail($id, $tenantId)
    {
        $committee = DB::table('committees')
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (!$committee) {
            return ['committee' => null, 'paragraphs' => []];
        }

        $committeeDetails = DB::table('committee_details')
            ->where('committee_id', $id)
            ->orderBy('paragraph_order', 'asc')
            ->pluck('paragraph_text')
            ->toArray();

        return [
            'committee' => $committee,
            'paragraphs' => $committeeDetails
        ];
    }
}
