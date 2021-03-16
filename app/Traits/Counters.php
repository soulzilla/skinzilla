<?php

namespace App\Traits;

use App\Models\Counters\Comment;
use App\Models\Counters\Like;
use App\Models\Counters\Rating;
use App\Models\Counters\View;
use Illuminate\Support\Facades\Cache;

/**
 * Trait Counters
 * @package App\Traits
 *
 * @property View[] $views
 * @property int $views_count
 * @property Like[] $likes
 * @property int $likes_count
 * @property Comment[] $comments
 * @property int $comments_count
 * @property Rating[] $ratings
 * @property int $ratings_count
 */
trait Counters
{
    private $defaultUserId = -1;

    public function views()
    {
        return $this->hasMany(View::class, 'entity_id', 'id')->where('entity_table', $this->getTable());
    }

    public function addView()
    {
        $cacheKey = request()->fingerprint() . '-' . $this->getTable() . '-' . $this->id;
        $exists = Cache::has($cacheKey);
        if ($exists) {
            return;
        }

        $view = new View();
        $view->entity_table = $this->getTable();
        $view->entity_id = $this->id;
        $view->fingerprint = request()->fingerprint();
        $view->user_id = auth()->user() ? auth()->user()->id : $this->defaultUserId;
        $view->save();

        Cache::add($cacheKey, 1, 1200);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'entity_id', 'id')
            ->where('entity_table', $this->getTable());
    }

    public function like()
    {
        return $this->hasOne(Like::class, 'entity_id', 'id')
            ->where('entity_table', $this->getTable())->where('user_id', auth()->user() ? auth()->user()->id : $this->defaultUserId);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'entity_id', 'id')
            ->where('entity_table', $this->getTable());
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'entity_id', 'id')
            ->where('entity_table', $this->getTable())->where('user_id', auth()->user() ? auth()->user()->id : $this->defaultUserId);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'entity_id', 'id')
            ->where('entity_table', $this->getTable())
            ->whereNull('deleted_at');
    }
}
