<?php

namespace App\Models;

use App\Traits\Counters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 * @package App\Models
 *
 * @property int $id
 * @property int $entity_id
 * @property string $entity_table
 * @property int $user_id
 * @property string $content
 * @property int $parent_id
 * @property int $branch_id
 * @property boolean $blocked
 * @property string $block_reason
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comment[] $branch
 * @property Comment $parent
 * @property Comment $head
 */
class Comment extends Model
{
    use HasFactory, Counters;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'content';

    protected $guarded = [self::COLUMN_ID];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasMany(Comment::class, 'branch_id', 'id')->orderBy('created_at');
    }

    public function parent()
    {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }

    public function head()
    {
        return $this->hasOne(Comment::class, 'id', 'branch_id');
    }
}
