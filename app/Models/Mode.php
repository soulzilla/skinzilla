<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Mode
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property int $entity_id
 * @property string $entity_table
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Mode extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';

    protected $guarded = [self::COLUMN_ID];
}
