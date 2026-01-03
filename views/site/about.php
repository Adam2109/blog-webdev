<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Про автора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <div class="row justify-content-center">
        <div class="col-md-9"> <div class="card shadow-sm mb-4" style="background-color: #2c2c2c; border: 1px solid #444;">
                <div class="card-body text-center text-white">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-info" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="card-title fw-bold">Адаменко Артем</h2>
                    <p class="text-white-50 mb-1" style="font-size: 1.1em;">Студент групи <strong class="text-white">ЦТ.м-52</strong></p>
                    <p class="text-secondary small text-uppercase spacing-2">Сумський державний університет</p>
                </div>
            </div>

            <div class="card shadow-sm" style="background-color: #2c2c2c; border: 1px solid #444;">
                <div class="card-header bg-secondary text-white border-0">
                    <i class="bi bi-gear-wide-connected"></i> Інформація про проект
                </div>
                <div class="card-body text-white">
                    <p class="card-text lead" style="font-size: 1rem;">
                        Цей вебдодаток розроблено в рамках виконання курсової роботи
                        з дисципліни <em>«Проектування веб-орієнтованих інформаційних систем»</em>.
                    </p>

                    <hr class="border-secondary my-4">

                    <h5 class="text-info mb-3"><i class="bi bi-code-slash"></i> Технічний стек:</h5>
                    <ul class="list-unstyled ps-2">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>PHP & Yii2 Framework</strong> — реалізація архітектурного патерну MVC.
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>MySQL</strong> — реляційна база даних.
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Active Record</strong> — ORM для безпечної роботи з даними.
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Bootstrap 5 & CSS3</strong> — адаптивний дизайн із підтримкою Dark Mode.
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Summernote JS</strong> — інтеграція візуального WYSIWYG редактора.
                        </li>
                    </ul>

                    <hr class="border-secondary my-4">

                    <h5 class="text-info mb-3"><i class="bi bi-list-check"></i> Реалізований функціонал:</h5>
                    <ul class="ps-3">
                        <li class="mb-2">Автентифікація, реєстрація та рольова модель доступу (Admin/User).</li>
                        <li class="mb-2">Повноцінний <strong>CRUD для статей</strong> (створення, читання, редагування, видалення).</li>

                        <li class="mb-2">
                            <strong>Система статусів контенту:</strong> розділення на "Чернетки" та "Опубліковано" (візуальне маркування в адмін-панелі).
                        </li>
                        <li class="mb-2">
                            <strong>Візуальний редактор:</strong> форматування тексту, списки, робота з зображеннями.
                        </li>
                        <li class="mb-2">
                            <strong>Гібридний алгоритм рекомендацій:</strong> автоматичний підбір "Схожих статей" за пріоритетом тегів, а потім категорій.
                        </li>
                        <li class="mb-2">
                            <strong>Розширене сортування та фільтрація:</strong> сортування за датою/популярністю, пошук по заголовках і тегах.
                        </li>

                        <li class="mb-2">Система коментування та лічильник переглядів.</li>
                        <li class="mb-2">Особистий кабінет користувача з історією активності.</li>
                    </ul>
                </div>

                <div class="card-footer text-muted text-center border-top border-secondary" style="background-color: #252525;">
                    &copy; <?= date('Y') ?> Всі права захищено.
                </div>
            </div>

        </div>
    </div>
</div>