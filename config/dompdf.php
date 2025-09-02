<?php
return [
    'show_warnings' => true, // Enable warnings to see font issues
    'public_path' => null,
    'convert_entities' => true,
    'options' => [
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),
        'allowed_protocols' => ['file://' => ['rules' => []], 'http://' => ['rules' => []], 'https://' => ['rules' => []]],
        'log_output_file' => storage_path('logs/dompdf.log'),
        'enable_font_subsetting' => true,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',
        'default_font' => 'DejaVu Sans', // Temporarily use DejaVu Sans
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => false,
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
        'font_data' => [
            'pyidaungsu' => [
                'R'  => 'Pyidaungsu-2.5.3_Regular.ttf',
                'B'  => 'Pyidaungsu-2.5.3_Regular.ttf', // You can use a Bold version if you have it
                'I'  => 'Pyidaungsu-2.5.3_Regular.ttf', // Italic
                'BI' => 'Pyidaungsu-2.5.3_Regular.ttf', // Bold Italic

                'useOTL' => 0xFF,
                'useKashida' => 50,
            ],
            'dejavu sans' => [ // Ensure DejaVu Sans is available
                'R' => 'DejaVuSans.ttf',
                'I' => 'DejaVuSans-Oblique.ttf',
                'B' => 'DejaVuSans-Bold.ttf',
                'BI' => 'DejaVuSans-BoldOblique.ttf',
            ],
        ],
    ],
];
