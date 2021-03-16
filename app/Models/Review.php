<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    const COLUMN_ID             = 'id';
    const COLUMN_NAME           = 'content';

    protected $guarded = [self::COLUMN_ID];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
