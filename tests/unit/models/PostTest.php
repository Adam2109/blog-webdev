<?php
namespace tests\unit\models;
use app\models\Post;

class PostTest extends \Codeception\Test\Unit
{
    public function testValidatePost()
    {
        $post = new Post();

        // Тест 1: Перевірка пустого заголовка (очікуємо false)
        $post->title = null;
        $this->assertFalse($post->validate(['title']));

        // Тест 2: Перевірка занадто довгого заголовка
        $post->title = str_repeat('a', 300);
        $this->assertFalse($post->validate(['title']));

        // Тест 3: Перевірка коректних даних (очікуємо true)
        $post->title = 'Correct Title';
        $post->status = 1;
        $this->assertTrue($post->validate('title'));
    }
}