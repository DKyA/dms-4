<?php

/*

Structure:

'unique id key' => [
    'component' => 'component to by inserted',
    'data' => 'text to be used on the page',
    'attributes' => >['specials' => 'to be also', 'accessible' => 'on the page'],
    'subcomponents' => [
        'recursively nest more.'
    ],
];

$template = 'basic template to start of with. May help with structure. Default is none.';
//options: blob_middle

*/


$modules = [
    'login-form' => [
        'component' => 'form',
        'data' => ["$text[0] │ Přihlášení"],
        'subcomponents' => [
            'username' => [
                'component' => 'input',
                'data' => ['Uživatelské jméno'],
                'attributes' => ['type' => 'text', 'placeholder' => 'Přihlašovací jméno', 'required' => True], 
            ],
            'password' => [
                'component' => 'input',
                'data' => ['Heslo'], 
                'attributes' => ['type' => 'password', 'placeholder' => 'Vaše heslo', 'required' => True], 
            ],
            'submit' => [
                'component' => 'button',
                'data' => ['Přihlásit'], 
                'attributes' => ['type' => 'submit'], 
            ],
            'forgot-password' => [
                'type' => 'a',
                'data' => ['Zapomenuté heslo?'],
                'attributes' => ['href' => '#', 'type' => 'footnote'],
            ]
        ]
    ],
];

$template = 'blob_middle';