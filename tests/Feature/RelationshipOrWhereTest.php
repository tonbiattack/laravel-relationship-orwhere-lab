<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\WorkspacePost;
use App\Models\WorkspaceUser;
use App\Services\VisiblePostFinder;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

final class RelationshipOrWhereTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $schema = app('db')->connection()->getSchemaBuilder();
        $schema->dropIfExists('workspace_posts');
        $schema->dropIfExists('workspace_users');
        $schema->create('workspace_users', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        $schema->create('workspace_posts', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workspace_user_id');
            $table->string('title');
            $table->string('status');
            $table->boolean('featured')->default(false);
        });
    }

    public function test_published_posts_of_the_target_user_are_visible(): void
    {
        $owner = WorkspaceUser::query()->create(['name' => 'Aki']);
        $published = WorkspacePost::query()->create([
            'workspace_user_id' => $owner->id,
            'title' => 'owner published',
            'status' => 'published',
            'featured' => false,
        ]);

        $actualIds = app(VisiblePostFinder::class)->findFor($owner)->pluck('id')->all();

        self::assertSame([$published->id], $actualIds);
    }

    public function test_featured_posts_of_other_users_are_not_visible(): void
    {
        $owner = WorkspaceUser::query()->create(['name' => 'Aki']);
        $other = WorkspaceUser::query()->create(['name' => 'Ren']);
        $ownDraft = WorkspacePost::query()->create([
            'workspace_user_id' => $owner->id,
            'title' => 'owner draft',
            'status' => 'draft',
            'featured' => true,
        ]);
        $otherFeatured = WorkspacePost::query()->create([
            'workspace_user_id' => $other->id,
            'title' => 'other featured',
            'status' => 'draft',
            'featured' => true,
        ]);

        $actualIds = app(VisiblePostFinder::class)->findFor($owner)->pluck('id')->all();

        self::assertSame(
            [$ownDraft->id],
            $actualIds,
            '関連クエリのOR条件によって別ユーザーの注目投稿が混入してはならない'
        );
        self::assertNotContains($otherFeatured->id, $actualIds);
    }
}
