<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Banner
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $content
 * @property boolean $published
 * @property string $url
 * @property string $image
 * @property boolean $blank
 * @property string $created_at
 * @property string $updated_at
 */
class Banner extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'title';

    protected $guarded = [self::COLUMN_ID];
}
