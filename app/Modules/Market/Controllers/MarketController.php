<?php

namespace App\Modules\Market\Controllers;

use App\Models\Market;
use App\Modules\Market\Requests\MarketRequest;
use App\Modules\Market\Resources\MarketResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MarketController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Market::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Market::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return MarketResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param MarketRequest $request
     * @return JsonResponse
     */
    public function store(MarketRequest $request)
    {
        $data = $request->validated();
        $market = new Market($data);
        $market->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Market $market
     * @return MarketResource
     */
    public function show(Market $market)
    {
        return new MarketResource($market);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MarketRequest $request
     * @param Market $market
     * @return JsonResponse
     */
    public function update(MarketRequest $request, Market $market)
    {
        $data = $request->validated();
        $market->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Market $market
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Market $market)
    {
        $market->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function top()
    {
        $data = Market::query()
            ->where('published', true)
            ->where('recommendation_level', 1)
            ->orderBy('order', 'asc')
            ->withCount(['comments', 'views', 'likes'])
            ->limit(3)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function all(Request $request)
    {
        $sort = (int) $request->get('sort', 1);

        $data = Market::query()
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

        $model = Market::query()->where('id', $id)
            ->withCount(['views', 'ratings', 'likes'])
            ->with(['like', 'rating'])
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
