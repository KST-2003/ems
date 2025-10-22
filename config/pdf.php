<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'pyidaungsu', // Changed to your custom font
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => storage_path('fonts'), // Added a custom font directory
    'custom_font_data'         => [
                'pyidaungsu' => [
                    'R'  => 'Pyidaungsu-1.8.3_Regular.ttf',
                    'B'  => 'Pyidaungsu-2.5.3_Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
                'pyidaungsunumber' => [
                    'R' => 'Pyidaungsu-2.5.3_Numbers.ttf',
                ],
            ],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
