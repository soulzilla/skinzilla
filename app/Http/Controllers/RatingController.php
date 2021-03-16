<?php

namespace App\Http\Controllers;

use App\Models\Counters\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $entity_id = $request->input('entity_id');
        $entity_table = $request->input('entity_table');

        $model = Rating::query()
            ->where('user_id', $user_id)
            ->where('entity_id', $entity_id)
            ->where('entity_table', $entity_table)
            ->first();

        if (!$model) {
            $model = new Rating();
            $model->user_id = $user_id;
            $model->entity_table = $entity_table;
            $model->entity_id = $entity_id;
        }

        $model->rate = $request->input('rate');
        $model->save();

        return response()->json([
            'type' => 'success'
        ]);
    }
}
