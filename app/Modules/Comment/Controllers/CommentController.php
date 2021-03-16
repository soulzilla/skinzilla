<?php

namespace App\Modules\Comment\Controllers;

use App\Models\Comment;
use App\Modules\Comment\Requests\CommentRequest;
use App\Modules\Comment\Resources\CommentResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Comment::query()
            ->orderBy($column, $order)->paginate($pageSize);

        return CommentResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param CommentRequest $request
     * @return JsonResponse
     */
    public function store(CommentRequest $request)
    {
        $data = $request->validated();
        $comment = new Comment($data);
        $comment->user_id = auth()->user()->id;
        $comment->blocked = false;
        $comment->save();

        $comment->load(['user', 'parent' => function ($query) {
            $query->with(['user']);
        }]);
        $comment->loadCount(['likes']);

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created',
            'data' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $comment->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Comment $comment)
    {
        $comment->deleted_at = $comment->freshTimestampString();
        $comment->save();

        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted',
            'deleted_at' => $comment->deleted_at
        ]);
    }

    public function entity(Request $request)
    {
        $data = Comment::query()
            ->where('entity_id', $request->get('entity_id'))
            ->where('entity_table', $request->get('entity_table'))
            ->whereNull('branch_id')
            ->with(['branch' => function ($query) {
                $query->with(['user', 'like', 'parent' => function ($query) {
                    $query->with(['user']);
                }])->withCount(['likes']);
            }, 'user', 'like'])
            ->withCount(['likes'])
            ->orderBy('created_at')
            ->paginate(10);

        return CommentResource::collection($data);
    }
}
