<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Limits Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all content size limits for the application
    |
    */

    'crush' => [
        'title_max' => 100,
        'text_min' => 10,
        'text_max' => 400,
        'image_max_size' => 3072, // KB (3MB)
        'image_max_width' => 4096,
        'image_max_height' => 4096,
        'categories_max' => 5,
        'allowed_mimes' => ['jpeg', 'jpg', 'png', 'gif'],
    ],

    'comment' => [
        'text_min' => 1,
        'text_max' => 400,
    ],

    'message' => [
        'content_min' => 10,
        'content_max' => 400,
    ],
];
