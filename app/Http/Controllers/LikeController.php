<?php

namespace App\Http\Controllers;

use App\Models\Counters\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->entity_id = $entity_id = $request->input('entity_id');
        $like->entity_table = $entity_table = $request->input('entity_table');
        $like->save();

        $likes_count = Like::query()
            ->where('entity_id', $entity_id)
            ->where('entity_table', $entity_table)
            ->count();

        return response()->json([
            'data' => $like,
            'likes_count' => $likes_count
        ]);
    }

    public function destroy(Request $request)
    {
        $entity_id = $request->input('entity_id');
        $entity_table = $request->input('entity_table');

        $like = Like::query()
            ->where('entity_id', $entity_id)
            ->where('entity_table', $entity_table)
            ->where('user_id', auth()->user()->id)
            ->first();

        $like->delete();

        $likes_count = Like::query()
            ->where('entity_id', $entity_id)
            ->where('entity_table', $entity_table)
            ->count();

        return response()->json([
            'likes_count' => $likes_count
        ]);
    }
}
