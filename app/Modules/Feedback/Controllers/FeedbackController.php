<?php

namespace App\Modules\Feedback\Controllers;

use App\Models\Feedback;
use App\Modules\Feedback\Requests\FeedbackRequest;
use App\Modules\Feedback\Resources\FeedbackResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedbackController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Feedback::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Feedback::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return FeedbackResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param FeedbackRequest $request
     * @return JsonResponse
     */
    public function store(FeedbackRequest $request)
    {
        $data = $request->validated();
        $feedback = new Feedback($data);
        $feedback->user_id = auth()->user()->id;
        $feedback->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Feedback $feedback
     * @return FeedbackResource
     */
    public function show(Feedback $feedback)
    {
        return new FeedbackResource($feedback);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FeedbackRequest $request
     * @param Feedback $feedback
     * @return JsonResponse
     */
    public function update(FeedbackRequest $request, Feedback $feedback)
    {
        $data = $request->validated();
        $feedback->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Feedback $feedback
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

}
