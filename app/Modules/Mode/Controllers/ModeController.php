<?php

namespace App\Modules\Mode\Controllers;

use App\Models\Mode;
use App\Modules\Mode\Requests\ModeRequest;
use App\Modules\Mode\Resources\ModeResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModeController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Mode::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Mode::COLUMN_NAME, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return ModeResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param ModeRequest $request
     * @return JsonResponse
     */
    public function store(ModeRequest $request)
    {
        $data = $request->validated();
        $mode = new Mode($data);
        $mode->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Mode $mode
     * @return ModeResource
     */
    public function show(Mode $mode)
    {
        return new ModeResource($mode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ModeRequest $request
     * @param Mode $mode
     * @return JsonResponse
     */
    public function update(ModeRequest $request, Mode $mode)
    {
        $data = $request->validated();
        $mode->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Mode $mode
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Mode $mode)
    {
        $mode->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

}
