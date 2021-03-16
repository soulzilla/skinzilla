<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CompositionItem
 * @package App\Models
 *
 * @property int $id
 * @property int $composition_id
 * @property int $skin_id
 * @property string $created_at
 * @property string $updated_at
 */
class CompositionItem extends Model
{
    public function skin()
    {
        return $this->hasOne(Skin::class, 'id', 'skin_id');
    }
}
