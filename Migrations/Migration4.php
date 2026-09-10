<?php

namespace Tywed\Webtrees\Module\NewsMenu\Migrations;

use Fisharebest\Webtrees\Schema\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

/**
 * Update the database schema to add indexes for performance optimization
 */
class Migration4 implements MigrationInterface
{
    /**
     * Upgrade the database schema
     */
    public function upgrade(): void
    {
        if (DB::schema()->hasTable('news')) {
            $this->addIndex('news', 'gedcom_id', 'news_gedcom_id_index');
            $this->addIndex('news', 'category_id', 'news_category_id_index');
            $this->addIndex('news', ['is_pinned', 'updated'], 'news_pinned_updated_index');
            $this->addIndex('news', 'updated', 'news_updated_index');
            $this->addIndex('news', 'view_count', 'news_view_count_index');
        }

        if (DB::schema()->hasTable('news_comments')) {
            $this->addIndex('news_comments', 'news_id', 'news_comments_news_id_index');
            $this->addIndex('news_comments', 'updated', 'news_comments_updated_index');
        }

        if (DB::schema()->hasTable('news_likes')) {
            $this->addIndex('news_likes', 'news_id', 'news_likes_news_id_index');
        }

        if (DB::schema()->hasTable('comments_likes')) {
            $this->addIndex('comments_likes', 'comments_id', 'comments_likes_comments_id_index');
        }
    }

    /**
     * @param string          $table
     * @param string|string[] $columns
     * @param string          $indexName
     */
    private function addIndex(string $table, $columns, string $indexName): void
    {
        if (!DB::schema()->hasColumn($table, is_array($columns) ? $columns[0] : $columns)) {
            return;
        }

        if (DB::schema()->hasIndex($table, $indexName)) {
            return;
        }

        DB::schema()->table($table, function (Blueprint $table) use ($columns, $indexName): void {
            $table->index($columns, $indexName);
        });
    }
}
