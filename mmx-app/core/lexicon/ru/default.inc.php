<?php

$_tmp = [
    'menu_name' => 'mmxApp',
    'menu_desc' => 'Заготовка для создания нового mmx приложения',
    'actions' => [
        'create' => 'Создать',
        'edit' => 'Изменить',
        'submit' => 'Отправить',
        'cancel' => 'Отмена',
        'delete' => 'Удалить',
    ],
    'models' => [
        'item' => [
            'title_one' => 'Предмет',
            'title_many' => 'Предметы',
            'id' => 'Id',
            'title' => 'Название',
            'active' => 'Активно',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ],
    ],
    'components' => [
        'no_data' => 'Нет данных для вывода',
        'no_results' => 'Результатов не найдено',
        'records' => 'Записей нет | {total} запись | {total} записи | {total} записей',
        'query' => 'Поиск...',
        'delete' => [
            'title' => 'Требуется подтверждение',
            'confirm' => 'Вы уверены, что хотите удалить эту запись?',
        ],
    ],
    'snippets' => [
        'nocss' => 'Не загружать собранный CSS',
    ],
    'errors' => [
        'item' => [
            'title_unique' => 'Заголовок предмета должен быть уникальным.',
        ],
    ],
];

/** @var array $_lang */
$_lang = array_merge($_lang, MMX\App\App::prepareLexicon($_tmp, MMX\App\App::NAMESPACE));

$_tmp = [
    'some-setting' => 'Какая-то настройка',
    'some-setting_desc' => 'Описание какой-то настройки',
];
$_lang = array_merge($_lang, MMX\App\App::prepareLexicon($_tmp, 'setting_' . MMX\App\App::NAMESPACE));

unset($_tmp);