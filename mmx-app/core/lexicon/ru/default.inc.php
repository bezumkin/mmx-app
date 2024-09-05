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
        'confirm' => [
            'title' => 'Требуется подтверждение',
            'message' => 'Вы уверены?',
        ],
        'table' => [
            'no_data' => 'Нет данных для вывода',
            'no_results' => 'Результатов не найдено',
            'records' => 'Записей нет | {total} запись | {total} записи | {total} записей',
            'query' => 'Поиск...',
            'delete' => [
                'title' => 'Требуется подтверждение',
                'confirm' => 'Вы уверены, что хотите удалить эту запись?',
            ],
        ],
        'datePicker' => [
            'placeholder_date' => 'Выберите дату',
            'placeholder_range' => 'Выберите период',
            'months' => [
                'январь',
                'февраль',
                'март',
                'апрель',
                'май',
                'июнь',
                'июль',
                'август',
                'сентябрь',
                'октябрь',
                'ноябрь',
                'декабрь',
            ],
            'monthsShort' => [
                'янв.',
                'февр.',
                'март',
                'апр.',
                'май',
                'июнь',
                'июль',
                'авг.',
                'сент.',
                'окт.',
                'нояб.',
                'дек.',
            ],
            'weekdays' => ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            'weekdaysShort' => ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'],
            'weekdaysMin' => ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'],
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