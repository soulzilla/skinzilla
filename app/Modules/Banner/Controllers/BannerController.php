<?php

namespace App\Modules\Banner\Controllers;

use App\Models\Banner;
use App\Modules\Banner\Requests\BannerRequest;
use App\Modules\Banner\Resources\BannerResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BannerController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Banner::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Banner::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return BannerResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param BannerRequest $request
     * @return JsonResponse
     */
    public function store(BannerRequest $request)
    {
        $data = $request->validated();
        $banner = new Banner($data);
        $banner->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Banner $banner
     * @return BannerResource
     */
    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BannerRequest $request
     * @param Banner $banner
     * @return JsonResponse
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        $data = $request->validated();
        $banner->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Banner $banner
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function top(Request $request)
    {
        $data = Banner::query()
            ->where('published', 1)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
