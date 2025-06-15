<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{

    public function createGroupPage()
    {
        return view('Backendpages.Groups.creategroup');
    }


    public function groupListPage()
    {
        return view('Backendpages.Groups.grouplistpage');
    }

    public function store(Request $request)
    {
        $id = DB::table('groups')->insertGetId([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['id' => $id, 'message' => 'Group Created Succesfully']);
    }



    public function list()
    {
        return DB::table('groups')->get();
    }

    public function show($id)
    {
        $group = DB::table('groups')->where('id', $id)->first();

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return response()->json(['data' => $group]);
    }


    public function update(Request $request, $id)
    {
        DB::table('groups')->where('id', $id)->update([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Updated successfully']);
    }

    public function statusChange(Request $request, $id)
    {
        DB::table('groups')->where('id', $id)->update([
            'status' => $request->status
    
        ]);
        return response()->json(['message' => 'Status Chnaged successfully']);
    }

    public function destroy($id)
    {
        // First delete related group_details
        DB::table('group_details')->where('g_id', $id)->delete();

        // Then delete the group
        DB::table('groups')->where('id', $id)->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
