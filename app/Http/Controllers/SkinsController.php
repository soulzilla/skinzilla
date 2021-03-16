<?php

namespace App\Http\Controllers;

use App\Enums\GunsEnum;
use App\Models\Skin;
use App\Resources\SkinsResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SkinsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');

        $query = Skin::query()
            ->where('cost', '<>', 0)
            ->when($request->filled('gun_type'), function (Builder $query) use ($request) {
                $gunType = (int) $request->get('gun_type');
                $query->whereIn('gun_type', GunsEnum::getGroup($gunType));
            })
            ->when($request->filled('quality'), function (Builder $query) use ($request) {
                $query->whereIn('quality', $request->get('quality'));
            })
            ->when($request->filled('rarity'), function (Builder $query) use ($request) {
                $query->whereIn('rarity', $request->get('rarity'));
            })
            ->when($request->filled('name'), function (Builder $query) use ($request) {
                $query->where(Skin::COLUMN_NAME, 'ilike', '%' . $request->get('name') . '%');
                $query->orWhere('aliases', 'ilike', '%' . $request->get('name') . '%');
            })
            ->when($request->filled('min_cost'), function (Builder $query) use ($request) {
                $query->where('cost', '>', $request->get('min_cost'));
            })
            ->when($request->filled('max_cost'), function (Builder $query) use ($request) {
                $query->where('cost', '<', $request->get('max_cost'));
            })
            ->when($request->filled('skin_id'), function (Builder $query) use ($request) {
                $query->whereNotIn('id', $request->get('skin_id'));
            })
            ->orderBy('cost', $sort)
            ->paginate(50);

        return SkinsResource::collection($query);
    }
}
