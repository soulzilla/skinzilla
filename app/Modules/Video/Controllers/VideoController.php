<?php

namespace App\Modules\Video\Controllers;

use App\Models\Video;
use App\Modules\Video\Requests\VideoRequest;
use App\Modules\Video\Resources\VideoResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideoController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Video::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Video::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return VideoResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param VideoRequest $request
     * @return JsonResponse
     */
    public function store(VideoRequest $request)
    {
        $data = $request->validated();
        $video = new Video($data);
        $video->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     * @return VideoResource
     */
    public function show(Video $video)
    {
        return new VideoResource($video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VideoRequest $request
     * @param Video $video
     * @return JsonResponse
     */
    public function update(VideoRequest $request, Video $video)
    {
        $data = $request->validated();
        $video->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Video $video
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function latest()
    {
        $data = Video::query()
            ->limit(5)
            ->latest('created_at')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
