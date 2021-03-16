<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Box
 * @package App\Models
 *
 * @property int $id
 * @property int $gambling_id
 * @property string $cost
 * @property string $name
 * @property string $url
 */
class Box extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';

    protected $guarded = [self::COLUMN_ID];
}
