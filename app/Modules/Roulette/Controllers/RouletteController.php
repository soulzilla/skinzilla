<?php

namespace App\Modules\Roulette\Controllers;

use App\Models\Roulette;
use App\Modules\Roulette\Requests\RouletteRequest;
use App\Modules\Roulette\Resources\RouletteResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RouletteController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Roulette::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Roulette::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return RouletteResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param RouletteRequest $request
     * @return JsonResponse
     */
    public function store(RouletteRequest $request)
    {
        $data = $request->validated();
        $roulette = new Roulette($data);
        $roulette->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Roulette $roulette
     * @return RouletteResource
     */
    public function show(Roulette $roulette)
    {
        return new RouletteResource($roulette);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RouletteRequest $request
     * @param Roulette $roulette
     * @return JsonResponse
     */
    public function update(RouletteRequest $request, Roulette $roulette)
    {
        $data = $request->validated();
        $roulette->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Roulette $roulette
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Roulette $roulette)
    {
        $roulette->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function top()
    {
        $data = Roulette::query()
            ->where('published', true)
            ->where('recommendation_level', 1)
            ->orderBy('order', 'asc')
            ->withCount(['comments', 'views', 'likes'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function all(Request $request)
    {
        $sort = (int) $request->get('sort', 1);

        $data = Roulette::query()
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

        $model = Roulette::query()->where('id', $id)
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
