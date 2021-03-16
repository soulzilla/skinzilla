<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Skin
 * @package App\Models
 * @property int $id
 * @property int $gun_type
 * @property string $name
 * @property string $cost
 * @property int $quality
 * @property int $rarity
 * @property boolean $stat_track
 * @property boolean $souvenir
 * @property string $image
 * @property string $aliases
 */
class Skin extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';

    protected $guarded = [self::COLUMN_ID];
}
