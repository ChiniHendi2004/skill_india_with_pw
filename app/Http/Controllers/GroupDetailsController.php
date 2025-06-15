<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GroupDetailsController extends Controller
{
    public function addDetailsGroupPage($g_id)
    {
        return view('Backendpages.Groups.AddGroupDetails', ['g_id' => $g_id]);
    }
    public function viewDetailsGroupPage($g_id)
    {
        return view('Backendpages.Groups.ViewGroupDetails', ['g_id' => $g_id]);
    }
    public function store(Request $request)
    {
        $id = DB::table('group_details')->insertGetId([
            'g_id' => $request->g_id,
            'value' => $request->value,
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['id' => $id, 'message' => 'Values Added Succesfully'], 200);
    }

    public function index()
    {
        return DB::table('group_details')
            ->join('variations', 'group_details.g_id', '=', 'variations.id')
            ->select('group_details.*', 'variations.name as variation_name')
            ->get();
    }

    public function show($id)
    {
        return DB::table('group_details')->where('id', $id)->first();
    }


    public function viewDetails($id)
    {
        return DB::table('groups')->where('id', $id)->first();
    }


    public function idWiseDetailsList($id)
    {
        return DB::table('group_details')->where('g_id', $id)->get();
    }

    public function select($id)
    {
        return DB::table('group_details')->where('id', $id)->first();
    }

    public function List()
    {
        return DB::table('group_details')->get();
    }

    public function update(Request $request, $id)
    {
        DB::table('group_details')->where('id', $id)->update([
            'value' => $request->value,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        DB::table('group_details')->where('id', $id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function statusChange(Request $request, $id)
    {
        DB::table('group_details')->where('id', $id)->update([
            'status' => $request->status

        ]);
        return response()->json(['message' => 'Status Chnaged successfully']);
    }
}
