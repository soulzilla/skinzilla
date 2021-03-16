<?php

namespace App\Modules\Review\Controllers;

use App\Models\Review;
use App\Modules\Review\Requests\ReviewRequest;
use App\Modules\Review\Resources\ReviewResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Review::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Review::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return ReviewResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function store(ReviewRequest $request)
    {
        $data = $request->validated();
        $review = new Review($data);
        $review->user_id = auth()->user()->id;
        $review->published = false;
        $review->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Review $review
     * @return ReviewResource
     */
    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(ReviewRequest $request, Review $review)
    {
        $data = $request->validated();
        $review->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Review $review
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

    public function top()
    {
        $data = Review::query()
            ->where('published', true)
            ->with('user')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
