<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Import
    |--------------------------------------------------------------------------
    |
    | This file is a custom addition for setting constants used to import data.
    |
    */
    'group_types' => [
        'college_board' => [
            'filter_by_location' => true,
            'name' => 'The College Board',
            'path' => 'https://gist.githubusercontent.com/aaronschachter/2933b5d29bbee4a866de2de49a1f35f7/raw/452d267228d2478de6e845982291c7510cab08cf/groups.csv',
        ],
        'rhode_island' => [
            'filter_by_location' => false,
            'name' => 'Rhode Island',
            'path' => 'https://gist.githubusercontent.com/aaronschachter/79d0d78289d4d9ee09287155d84a5d39/raw/88707a827857c0529e09ac5583a08ae1ad4f9289/groups.csv',
        ],
    ],
];
