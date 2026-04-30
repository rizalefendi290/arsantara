<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Listing Image Watermark
    |--------------------------------------------------------------------------
    |
    | Path is relative to the public directory. By default this uses the
    | project logo, but it can be changed with WATERMARK_IMAGE in .env.
    |
    */

    'image' => env('WATERMARK_IMAGE', 'images/logo.png'),
    'position' => env('WATERMARK_POSITION', 'center'),
    'opacity' => (int) env('WATERMARK_OPACITY', 55),
    'width_ratio' => (float) env('WATERMARK_WIDTH_RATIO', 0.22),
    'margin_ratio' => (float) env('WATERMARK_MARGIN_RATIO', 0.035),
];
