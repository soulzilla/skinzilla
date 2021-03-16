<?php

namespace App\Models\Counters;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 * @package App\Models\Counters
 * @property int $id
 * @property int $user_id
 * @property int $entity_id
 * @property string $entity_table
 * @property string $content
 * @property int $parent_id
 * @property int $branch_id
 * @property boolean $blocked
 * @property string $block_reason
 */
class Comment extends \App\Models\Comment
{

}
