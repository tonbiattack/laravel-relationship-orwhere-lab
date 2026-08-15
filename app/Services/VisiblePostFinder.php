<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WorkspaceUser;
use Illuminate\Database\Eloquent\Collection;

final class VisiblePostFinder
{
    /** @return Collection<int, \App\Models\WorkspacePost> */
    public function findFor(WorkspaceUser $user): Collection
    {
        return $user->posts()
            ->where('status', 'published')
            ->orWhere('featured', true)
            ->orderBy('id')
            ->get();
    }
}
