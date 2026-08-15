# Laravel関連クエリ `orWhere` デバッグラボ

`$user->posts()` に対する `orWhere()` をグループ化しないため、対象外ユーザーの注目投稿まで返してしまう挙動を再現する。

| 項目 | 内容 |
|---|---|
| Laravel | 13.25.0 |
| PHP | 8.3.6 |
| DB | SQLite（テスト時はインメモリ） |
| 対象テスト | `php artisan test tests/Feature/RelationshipOrWhereTest.php` |

初期状態では、関連クエリに別ユーザーの注目投稿が混入しないべきテストが失敗する。修正後は `where()` クロージャでOR条件をグループ化し、外部キー制約を保ったまま検索できることを回帰テストで固定する。
