<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Про автора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title">Адаменко Артем</h2>
                    <p class="text-muted mb-1">Студент групи <strong>ЦТ.м-52</strong></p>
                    <p class="text-secondary">Сумський державний університет</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-info-circle"></i> Інформація про проект
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Цей вебдодаток розроблено в рамках виконання курсової роботи
                        з дисципліни <em>«Проектування веб-орієнтованих інформаційних систем»</em>.
                    </p>

                    <h5 class="mt-4 text-primary">Технічний стек:</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>PHP & Yii2 Framework</strong> — реалізація патерну MVC.
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>MySQL</strong> — база даних.
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Active Record</strong> — робота з даними (ORM).
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <strong>Bootstrap 5</strong> — адаптивний дизайн інтерфейсу.
                        </li>
                    </ul>

                    <h5 class="mt-4 text-primary">Реалізований функціонал:</h5>
                    <ul>
                        <li>Автентифікація та реєстрація користувачів.</li>
                        <li>Рольова модель доступу.</li>
                        <li>CRUD для статей (створення, читання, редагування, видалення).</li>
                        <li>Система коментування з деревоподібною структурою (відповіді).</li>
                        <li>Лайки (AJAX) та лічильник переглядів.</li>
                        <li>Особистий кабінет користувача.</li>
                    </ul>
                </div>
                <div class="card-footer text-muted text-center">
                    &copy; <?= date('Y') ?> Всі права захищено.
                </div>
            </div>

        </div>
    </div>
</div>