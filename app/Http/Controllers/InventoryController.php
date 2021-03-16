<?php

namespace App\Http\Controllers;

use App\Enums\GunsEnum;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $data = InventoryItem::query()
            ->where('user_id', auth()->user()->id)
            ->with(['skin'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $gun_type = $request->input('gun_type');
        $side = $request->input('side');

        $model = InventoryItem::query()
            ->where('user_id', $user_id)
            ->where('side', $side)
            ->where('gun_type', $gun_type)
            ->first();

        if (!$model) {
            $model = new InventoryItem();
            $model->user_id = $user_id;
            $model->gun_type = $gun_type;
            $model->side = $side;
        }

        $model->skin_id = $request->input('skin_id');
        $model->user_has_skin = false;
        $model->save();

        return response()->json([
            'type' => 'success',
            'preset' => GunsEnum::getPreset($model->gun_type),
            'group' => GunsEnum::getGroup($model->gun_type)
        ]);
    }

    public function destroy(Request $request)
    {
        $user_id = auth()->user()->id;
        $skin_id = $request->input('skin_id');
        $side = $request->input('side');
        $model = InventoryItem::query()
            ->where('user_id', $user_id)
            ->where('skin_id', $skin_id)
            ->where('side', $side)
            ->first();

        if ($model) {
            $model->delete();
        }

        return response()->json([
            'type' => 'success'
        ]);
    }
}
