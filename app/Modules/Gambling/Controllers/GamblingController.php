<?php

namespace App\Modules\Gambling\Controllers;

use App\Models\Gambling;
use App\Modules\Gambling\Requests\GamblingRequest;
use App\Modules\Gambling\Resources\GamblingResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GamblingController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Gambling::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Gambling::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return GamblingResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param GamblingRequest $request
     * @return JsonResponse
     */
    public function store(GamblingRequest $request)
    {
        $data = $request->validated();
        $gambling = new Gambling($data);
        $gambling->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Gambling $gambling
     * @return GamblingResource
     */
    public function show(Gambling $gambling)
    {
        return new GamblingResource($gambling);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GamblingRequest $request
     * @param Gambling $gambling
     * @return JsonResponse
     */
    public function update(GamblingRequest $request, Gambling $gambling)
    {
        $data = $request->validated();
        $gambling->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Gambling $gambling
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Gambling $gambling)
    {
        $gambling->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function top()
    {
        $data = Gambling::query()
            ->where('published', true)
            ->where('recommendation_level', 1)
            ->orderBy('order', 'asc')
            ->limit(3)
            ->withCount(['comments', 'views', 'likes'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function all(Request $request)
    {
        $sort = (int) $request->get('sort', 1);

        $data = Gambling::query()
            ->where('published', true)
            ->withCount(['comments', 'views', 'likes'])
            ->with(['rating'])
            ->withAvg('ratings', 'rate');

        switch ($sort) {
            case 1:
                $data->orderBy('order', 'asc');
                break;
            case 2:
                $data->orderBy('likes_count', 'desc');
                break;
            case 3:
                $data->orderBy('views_count', 'desc');
                break;
            case 4:
                $data->orderBy('comments_count', 'desc');
                break;
            case 5:
                $data->orderBy('ratings_avg_rate', 'desc');
                break;
        }

        return response()->json([
            'data' => $data->get()
        ]);
    }

    public function one(Request $request)
    {
        $uri = $request->getRequestUri();
        $uri = explode('/', $uri);
        $id = (int) end($uri);

        $model = Gambling::query()->where('id', $id)
            ->withCount(['views', 'ratings', 'likes'])
            ->with(['like', 'rating', 'modes'])
            ->first();

        if ($model) {
            $model->loadAvg('ratings', 'rate');
            $model->addView();
        }

        return response()->json([
            'data' => $model
        ]);
    }
}
