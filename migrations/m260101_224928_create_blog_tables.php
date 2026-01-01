<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_tables}}`.
 */
class m260101_224928_create_blog_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Таблиця користувачів
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // 2. Таблиця категорій
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);

        // 3. Таблиця статей
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'content' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'viewed' => $this->integer()->defaultValue(0),
            'user_id' => $this->integer(), // Автор
            'status' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer(),
        ]);

        // 4. Таблиця тегів
        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        // 5. Проміжна таблиця для зв'язку Стаття-Тег
        $this->createTable('{{%post_tag}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'tag_id' => $this->integer(),
        ]);

        // 6. Таблиця коментарів
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'status' => $this->integer()->defaultValue(0),
            'date' => $this->date(),
        ]);

        // --- Створення зовнішніх ключів для зв'язків ---


        $this->addForeignKey(
            'fk-post-category_id',
            '{{%post}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // Стаття -> Автор
        $this->addForeignKey(
            'fk-post-user_id',
            '{{%post}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Зв'язок тегів
        $this->addForeignKey(
            'fk-post_tag-post_id',
            '{{%post_tag}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-post_tag-tag_id',
            '{{%post_tag}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE'
        );

        // Коментарі -> Стаття
        $this->addForeignKey(
            'fk-comment-article_id',
            '{{%comment}}',
            'article_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );

        // Коментарі -> Користувач
        $this->addForeignKey(
            'fk-comment-user_id',
            '{{%comment}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-comment-user_id', '{{%comment}}');
        $this->dropForeignKey('fk-comment-article_id', '{{%comment}}');
        $this->dropForeignKey('fk-post_tag-tag_id', '{{%post_tag}}');
        $this->dropForeignKey('fk-post_tag-post_id', '{{%post_tag}}');
        $this->dropForeignKey('fk-post-user_id', '{{%post}}');
        $this->dropForeignKey('fk-post-category_id', '{{%post}}');

        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%post_tag}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%user}}');
    }
}