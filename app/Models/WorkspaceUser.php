<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class WorkspaceUser extends Model
{
    /** @var array<int, string> */
    protected $guarded = [];

    public $timestamps = false;

    public function posts(): HasMany
    {
        return $this->hasMany(WorkspacePost::class, 'workspace_user_id');
    }
}
