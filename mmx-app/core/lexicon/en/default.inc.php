<?php

$_tmp = [
    'menu_name' => 'mmxApp',
    'menu_desc' => 'Template for creating a new mmx application',
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'submit' => 'Submit',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
    ],
    'models' => [
        'item' => [
            'title_one' => 'Item',
            'title_many' => 'Items',
            'id' => 'Id',
            'title' => 'Title',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],
    ],
    'components' => [
        'confirm' => [
            'title' => 'Confirmation required',
            'message' => 'Are you sure?',
        ],
        'table' => [
            'no_data' => 'Nothing to display',
            'no_results' => 'Nothing found',
            'records' => 'No records | 1 record | {total} records',
            'query' => 'Search...',
            'delete' => [
                'title' => 'Confirmation required',
                'confirm' => 'Are you sure you want to delete this entry?',
            ],
        ],
        'datePicker' => [
            'placeholder_date' => 'Choose a date',
            'placeholder_range' => 'Choose a period',
            'months' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
            'monthsShort' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'weekdays' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'weekdaysShort' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'weekdaysMin' => ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        ],
    ],
    'snippets' => [
        'nocss' => 'Do not load frontend styles',
    ],
    'errors' => [
        'item' => [
            'title_unique' => 'The item title must be unique.',
        ],
    ],
];

/** @var array $_lang */
$_lang = array_merge($_lang, MMX\App\App::prepareLexicon($_tmp, MMX\App\App::NAMESPACE));

$_tmp = [
    'some-setting' => 'Setting title',
    'some-setting_desc' => 'Some setting description',
];
$_lang = array_merge($_lang, MMX\App\App::prepareLexicon($_tmp, 'setting_' . MMX\App\App::NAMESPACE));

unset($_tmp);