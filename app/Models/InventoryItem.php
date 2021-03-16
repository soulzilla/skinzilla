<?php

namespace App\Models;

use App\Enums\GunsEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryItem
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $skin_id
 * @property int $side
 * @property int $gun_type
 * @property bool $user_has_skin
 * @property string $created_at
 * @property string $updated_at
 */
class InventoryItem extends Model
{
    protected $table = 'inventory_items';

    protected $appends = ['group', 'preset'];

    public function skin()
    {
        return $this->hasOne(Skin::class, 'id', 'skin_id');
    }

    public function getGroupAttribute()
    {
        return GunsEnum::getGroup($this->gun_type);
    }

    public function getPresetAttribute()
    {
        return GunsEnum::getPreset($this->gun_type);
    }
}
