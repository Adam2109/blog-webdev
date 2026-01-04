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

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'image' => $this->string(), // ФОТО
            'role' => $this->integer()->defaultValue(0), // РОЛЬ (0-юзер, 1-адмін)
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'content' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'viewed' => $this->integer()->defaultValue(0),
            'user_id' => $this->integer(),
            'status' => $this->integer()->defaultValue(0),
            'category_id' => $this->integer(),
            'likes' => $this->integer()->defaultValue(0), // ЛАЙКИ
        ]);

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        $this->createTable('{{%post_tag}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'tag_id' => $this->integer(),
        ]);


        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'parent_id' => $this->integer()->defaultValue(null), // ВІДПОВІДІ
            'status' => $this->integer()->defaultValue(0),
            'date' => $this->date(),
        ]);


        $this->createTable('{{%post_like}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
        ]);


        $this->addForeignKey('fk-post-category', '{{%post}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-post-user', '{{%post}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->addForeignKey('fk-pt-post', '{{%post_tag}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-pt-tag', '{{%post_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE');

        $this->addForeignKey('fk-comment-post', '{{%comment}}', 'article_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-comment-user', '{{%comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-comment-parent', '{{%comment}}', 'parent_id', '{{%comment}}', 'id', 'CASCADE');

        $this->addForeignKey('fk-like-user', '{{%post_like}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-like-post', '{{%post_like}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('{{%post_like}}');
        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%post_tag}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%user}}');
    }
}