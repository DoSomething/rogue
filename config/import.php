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
            'path' => 'https://gist.githubusercontent.com/aaronschachter/79d0d78289d4d9ee09287155d84a5d39/raw/ba602f249e1562a2b04b206dcb0da0ba83fd5dd5/groups.csv',
        ],
    ],
];
