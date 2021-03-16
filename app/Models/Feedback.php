<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    const COLUMN_ID             = 'id';
    const COLUMN_NAME           = 'content';

    protected $guarded = [self::COLUMN_ID];
}
