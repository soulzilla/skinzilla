<?php

namespace App\Modules\Skin\Controllers;

use App\Models\Skin;
use App\Modules\Skin\Requests\SkinRequest;
use App\Modules\Skin\Resources\SkinResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkinController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Skin::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Skin::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return SkinResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param SkinRequest $request
     * @return JsonResponse
     */
    public function store(SkinRequest $request)
    {
        $data = $request->validated();
        $skin = new Skin($data);
        $skin->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Skin $skin
     * @return SkinResource
     */
    public function show(Skin $skin)
    {
        return new SkinResource($skin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SkinRequest $request
     * @param Skin $skin
     * @return JsonResponse
     */
    public function update(SkinRequest $request, Skin $skin)
    {
        $data = $request->validated();
        $skin->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Skin $skin
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Skin $skin)
    {
        $skin->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

}
