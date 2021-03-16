<?php

namespace App\Models;

use App\Traits\Counters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Gambling
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $name_canonical
 * @property string $website
 * @property string $promo_code
 * @property string $ref_link
 * @property string $logo
 * @property int $order
 * @property boolean $published
 * @property string $assessment
 * @property int $recommendation_level
 * @property string $created_at
 * @property string $updated_at
 * @property string $promo_code_description
 */
class Roulette extends Model
{
    use HasFactory, Counters;

    const COLUMN_ID             = 'id';
    const COLUMN_NAME           = 'name';

    protected $guarded = [self::COLUMN_ID];

    public function modes()
    {
        return $this->hasMany(Mode::class, 'entity_id', 'id')->where('entity_table', 'roulettes');
    }
}
