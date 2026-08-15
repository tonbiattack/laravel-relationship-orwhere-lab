<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class WorkspacePost extends Model
{
    /** @var array<int, string> */
    protected $guarded = [];

    public $timestamps = false;
}
