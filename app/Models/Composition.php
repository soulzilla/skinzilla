<?php

namespace App\Models;

use App\Models\Counters\Favourite;
use App\Traits\Counters;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Composition
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property boolean $is_copied
 * @property string $created_at
 * @property string $updated_at
 * @property int $original_id
 */
class Composition extends Model
{
    use Counters;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CompositionItem::class, 'composition_id', 'id');
    }

    public function skins()
    {
        return $this->hasManyThrough(Skin::class, CompositionItem::class, 'composition_id', 'id', 'id', 'skin_id');
    }

    public function favourite()
    {
        return $this->hasOne(Favourite::class, 'composition_id', 'id')
            ->where('user_id', auth()->user() ? auth()->user()->id : -1);
    }

    public function copy()
    {
        return $this->hasOne(Composition::class, 'original_id', 'id')
            ->where('user_id', auth()->user() ? auth()->user()->id : -1);
    }
}
