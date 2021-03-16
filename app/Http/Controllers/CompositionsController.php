<?php

namespace App\Http\Controllers;

use App\Models\Composition;
use App\Models\CompositionItem;
use App\Resources\CompositionResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompositionsController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 1);

        $query = Composition::query()
            ->where('is_copied', false)
            ->whereHas('items')
            ->with(['skins', 'user'])
            ->withSum('skins', 'cost')
            ->withCount(['skins', 'likes', 'views', 'comments']);

        switch ($sort) {
            case 2:
                $query->orderBy('likes_count', 'desc');
                break;
            case 3:
                $query->orderBy('views_count', 'desc');
                break;
            case 4:
                $query->orderBy('comments_count', 'desc');
                break;
            case 5:
                $query->orderBy('skins_sum_cost', 'desc');
                break;
            case 6:
                $query->whereHas('favourite')
                    ->join('favourites', 'favourites.composition_id', '=', 'compositions.id')
                    ->orderBy('favourites.created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $resource = $query->paginate(10);

        $collection = CompositionResource::collection($resource);

        $collection->map(function ($model) {
            $model->setRelation('skins', $model->skins->take(3));
            return $model;
        });

        return $collection;
    }

    public function latest()
    {
        $data = Composition::query()
            ->where('is_copied', false)
            ->whereHas('items')
            ->with(['skins', 'user'])
            ->withCount(['skins'])
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(function ($model) {
                $model->setRelation('skins', $model->skins->take(3));
                return $model;
            });

        return response()->json([
            'data' => $data
        ]);
    }

    public function my()
    {
        $data = Composition::query()
            ->where('user_id', auth()->user()->id)
            ->with(['skins'])
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function show(Request $request)
    {
        $uri = $request->getRequestUri();
        $uri = explode('/', $uri);
        $id = (int) end($uri);

        $model = Composition::query()->where('id', $id)
            ->withCount(['views', 'ratings', 'likes'])
            ->with(['like', 'rating', 'user', 'skins', 'copy', 'favourite'])
            ->withSum('skins', 'cost')
            ->first();

        if ($model) {
            $model->addView();
        }

        return response()->json([
            'data' => $model
        ]);
    }

    public function add(Request $request)
    {
        $composition_id = $request->input('composition_id');

        $composition = Composition::query()
            ->where('id', $composition_id)
            ->first();

        if ($composition->user_id !== auth()->user()->id) {
            throw new NotFoundHttpException();
        }

        $model = new CompositionItem();
        $model->composition_id = $composition_id;
        $model->skin_id = $request->input('skin_id');
        $model->save();
        $model->load('skin');

        return response()->json([
            'type' => 'success',
            'data' => $model->skin
        ]);
    }

    public function store(Request $request)
    {
        $composition = new Composition();
        $composition->name = $request->input('name');
        $composition->user_id = auth()->user()->id;
        $composition->save();

        return response()->json([
            'data' => $composition
        ]);
    }

    public function remove(Request $request)
    {
        $composition_id = $request->input('composition_id');

        $composition = Composition::query()
            ->where('id', $composition_id)
            ->first();

        if ($composition->user_id !== auth()->user()->id) {
            throw new NotFoundHttpException();
        }

        $item = CompositionItem::query()
            ->where('composition_id', $composition_id)
            ->where('skin_id', $request->input('skin_id'))
            ->first();

        if ($item) {
            $item->delete();
        }

        $data = CompositionItem::query()
            ->where('composition_id', $composition_id)
            ->with(['skin'])
            ->get();

        $skins = [];

        foreach ($data as $row) {
            $skins[] = $row->skin;
        }

        return response()->json([
            'data' => $skins
        ]);
    }

    public function destroy(Request $request)
    {
        $uri = $request->getRequestUri();
        $uri = explode('/', $uri);
        $id = (int) end($uri);

        $composition = Composition::query()->where('id', $id)->first();
        if ($composition->user_id === auth()->user()->id) {
            $items = CompositionItem::query()
                ->where('composition_id', $composition->id)
                ->get();

            foreach ($items as $item) {
                $item->delete();
            }

            $composition->delete();
        }

        return response()->json([
            'type' => 'success'
        ]);
    }

    public function clear(Request $request)
    {
        $uri = $request->getRequestUri();
        $uri = explode('/', $uri);
        $id = (int) end($uri);

        $composition = Composition::query()->where('id', $id)->first();
        if ($composition->user_id === auth()->user()->id) {
            $items = CompositionItem::query()
                ->where('composition_id', $composition->id)
                ->get();

            foreach ($items as $item) {
                $item->delete();
            }
        }

        return response()->json([
            'type' => 'success'
        ]);
    }

    public function copy(Request $request)
    {
        $composition_id = $request->input('id');
        $model = Composition::query()
            ->where('original_id', $composition_id)
            ->first();

        $composition = Composition::query()
            ->where('id', $composition_id)
            ->with(['items'])
            ->first();

        if (!$model) {
            $model = new Composition();
            $model->name = $composition->name;
            $model->user_id = auth()->user()->id;
            $model->is_copied = true;
            $model->original_id = $composition->id;
            $model->save();

            foreach ($composition->items as $item) {
                $copiedItem = new CompositionItem();
                $copiedItem->composition_id = $model->id;
                $copiedItem->skin_id = $item->skin_id;
                $copiedItem->save();
            }
        }

        return response()->json([
            'data' => $model
        ]);
    }
}
