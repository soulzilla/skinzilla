<?php

namespace App\Modules\Box\Controllers;

use App\Models\Box;
use App\Modules\Box\Requests\BoxRequest;
use App\Modules\Box\Resources\BoxResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BoxController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Box::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Box::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return BoxResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param BoxRequest $request
     * @return JsonResponse
     */
    public function store(BoxRequest $request)
    {
        $data = $request->validated();
        $box = new Box($data);
        $box->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Box $box
     * @return BoxResource
     */
    public function show(Box $box)
    {
        return new BoxResource($box);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BoxRequest $request
     * @param Box $box
     * @return JsonResponse
     */
    public function update(BoxRequest $request, Box $box)
    {
        $data = $request->validated();
        $box->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Box $box
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Box $box)
    {
        $box->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

}
