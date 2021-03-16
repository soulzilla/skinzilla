<?php

namespace App\Http\Controllers;

use App\Models\Counters\Favourite;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
    public function store(Request $request)
    {
        $id = $request->input('id');
        $user_id = auth()->user()->id;

        $model = Favourite::query()
            ->where('user_id', $user_id)
            ->where('composition_id', $id)
            ->first();

        if (!$model) {
            $model = new Favourite();
            $model->user_id = $user_id;
            $model->composition_id = $id;
            $model->save();
        }

        return response()->json([
            'data' => $model
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $user_id = auth()->user()->id;

        $model = Favourite::query()
            ->where('user_id', $user_id)
            ->where('composition_id', $id)
            ->first();

        if ($model) {
            $model->delete();
        }

        return response()->json([
            'type' => 'success'
        ]);
    }
}
