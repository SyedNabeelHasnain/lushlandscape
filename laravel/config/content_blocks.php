<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Block Type Registry (50 block types)
    |--------------------------------------------------------------------------
    | Defines all available block types, their labels, icons (Lucide), and
    | their default content schema. The 'fields' array describes what fields
    | the admin editor will render for each block.
    |
    | Categories:
    |   • Typography & Text (1–6)
    |   • Layout & Structure (7–12)
    |   • Media & Visual (13–20)
    |   • Interactive & Data (21–28)
    |   • Engagement & CTA (29–36)
    |   • Specialized / Industry (37–44)
    |   • Utility & Embed (45–50)
    */

    /*
    |--------------------------------------------------------------------------
    | Universal Wrapper Settings (applies to every block)
    |--------------------------------------------------------------------------
    | These fields render in a collapsible "Style" panel below each block's
    | content fields. Values are stored under content['_wrapper'].
    */
    'wrapper' => [
        'fields' => [
            ['key' => 'bg_color', 'label' => 'Background', 'type' => 'select',
                'options' => [
                    'none' => 'Transparent',
                    'white' => 'White',
                    'cream' => 'Cream',
                    'gray' => 'Light Gray',
                    'forest' => 'Forest Green',
                    'dark' => 'Dark',
                ]],
            ['key' => 'text_color', 'label' => 'Text Color', 'type' => 'select',
                'options' => [
                    'default' => 'Default',
                    'white' => 'White',
                    'dark' => 'Dark',
                    'forest' => 'Forest Green',
                ]],
            ['key' => 'padding_y', 'label' => 'Vertical Padding', 'type' => 'select',
                'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large']],
            ['key' => 'padding_x', 'label' => 'Horizontal Padding', 'type' => 'select',
                'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large']],
            ['key' => 'margin_top', 'label' => 'Top Spacing', 'type' => 'select',
                'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large']],
            ['key' => 'margin_bottom', 'label' => 'Bottom Spacing', 'type' => 'select',
                'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large']],
            ['key' => 'max_width', 'label' => 'Max Width', 'type' => 'select',
                'options' => ['full' => 'Full Width', 'xl' => '7xl (1280px)', 'lg' => '5xl (1024px)', 'md' => '3xl (768px)', 'sm' => 'xl (576px)']],
            ['key' => 'rounded', 'label' => 'Rounded Corners', 'type' => 'toggle'],
        ],
        'defaults' => [
            'bg_color' => 'none',
            'text_color' => 'default',
            'padding_y' => 'none',
            'padding_x' => 'none',
            'margin_top' => 'none',
            'margin_bottom' => 'none',
            'max_width' => 'full',
            'rounded' => false,
        ],
    ],

    'types' => [

        // =====================================================================
        // TYPOGRAPHY & TEXT
        // =====================================================================

        // 1. Heading
        'heading' => [
            'label' => 'Heading',
            'icon' => 'heading',
            'fields' => [
                ['key' => 'text',  'label' => 'Heading Text', 'type' => 'text'],
                ['key' => 'level', 'label' => 'Level (h1–h6)', 'type' => 'select',
                    'options' => ['h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h1' => 'H1']],
                ['key' => 'align', 'label' => 'Alignment', 'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right']],
            ],
            'defaults' => ['text' => '', 'level' => 'h2', 'align' => 'left'],
        ],

        // 2. Paragraph
        'paragraph' => [
            'label' => 'Paragraph',
            'icon' => 'align-left',
            'fields' => [
                ['key' => 'text', 'label' => 'Text', 'type' => 'textarea'],
            ],
            'defaults' => ['text' => ''],
        ],

        // 3. Rich Text
        'rich_text' => [
            'label' => 'Rich Text',
            'icon' => 'file-text',
            'fields' => [
                ['key' => 'html', 'label' => 'Content (HTML)', 'type' => 'textarea'],
            ],
            'defaults' => ['html' => ''],
        ],

        // 4. Section Header (heading + subtitle + decorative line)
        'section_header' => [
            'label' => 'Section Header',
            'icon' => 'type',
            'fields' => [
                ['key' => 'heading',  'label' => 'Heading',  'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'align',    'label' => 'Alignment', 'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center']],
                ['key' => 'tag',      'label' => 'Tag/Label', 'type' => 'text'],
                ['key' => 'show_line', 'label' => 'Show Decorative Line', 'type' => 'toggle'],
            ],
            'defaults' => ['heading' => '', 'subtitle' => '', 'align' => 'center', 'tag' => '', 'show_line' => true],
        ],

        // 5. Blockquote
        'blockquote' => [
            'label' => 'Blockquote',
            'icon' => 'quote',
            'fields' => [
                ['key' => 'text',   'label' => 'Quote Text',  'type' => 'textarea'],
                ['key' => 'author', 'label' => 'Author/Source', 'type' => 'text'],
                ['key' => 'style',  'label' => 'Style', 'type' => 'select',
                    'options' => ['bordered' => 'Left Border', 'card' => 'Card', 'large' => 'Large Centered']],
            ],
            'defaults' => ['text' => '', 'author' => '', 'style' => 'bordered'],
        ],

        // 6. Alert Box
        'alert_box' => [
            'label' => 'Alert / Notice Box',
            'icon' => 'alert-circle',
            'fields' => [
                ['key' => 'text',  'label' => 'Message',  'type' => 'textarea'],
                ['key' => 'title', 'label' => 'Title',    'type' => 'text'],
                ['key' => 'type',  'label' => 'Type',     'type' => 'select',
                    'options' => ['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error', 'tip' => 'Pro Tip']],
                ['key' => 'dismissible', 'label' => 'Dismissible', 'type' => 'toggle'],
            ],
            'defaults' => ['text' => '', 'title' => '', 'type' => 'info', 'dismissible' => false],
        ],

        // =====================================================================
        // LAYOUT & STRUCTURE
        // =====================================================================

        // 7. Two Column
        'two_column' => [
            'label' => 'Two Column',
            'icon' => 'columns',
            'fields' => [
                ['key' => 'left_html',  'label' => 'Left Column (HTML)',  'type' => 'textarea'],
                ['key' => 'right_html', 'label' => 'Right Column (HTML)', 'type' => 'textarea'],
                ['key' => 'ratio',      'label' => 'Column Ratio', 'type' => 'select',
                    'options' => ['1:1' => '50/50', '1:2' => '33/67', '2:1' => '67/33']],
                ['key' => 'reverse_mobile', 'label' => 'Reverse on Mobile', 'type' => 'toggle'],
            ],
            'defaults' => ['left_html' => '', 'right_html' => '', 'ratio' => '1:1', 'reverse_mobile' => false],
        ],

        // 8. Three Column
        'three_column' => [
            'label' => 'Three Column',
            'icon' => 'columns-3',
            'fields' => [
                ['key' => 'col1_html', 'label' => 'Column 1 (HTML)', 'type' => 'textarea'],
                ['key' => 'col2_html', 'label' => 'Column 2 (HTML)', 'type' => 'textarea'],
                ['key' => 'col3_html', 'label' => 'Column 3 (HTML)', 'type' => 'textarea'],
                ['key' => 'gap', 'label' => 'Gap Size', 'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large']],
            ],
            'defaults' => ['col1_html' => '', 'col2_html' => '', 'col3_html' => '', 'gap' => 'md'],
        ],

        // 9. Tabs
        'tabs' => [
            'label' => 'Tabs',
            'icon' => 'folder-open',
            'fields' => [
                ['key' => 'style', 'label' => 'Style', 'type' => 'select',
                    'options' => ['underline' => 'Underline', 'pills' => 'Pills', 'boxed' => 'Boxed']],
                ['key' => 'tabs', 'label' => 'Tab Panels', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'title',   'label' => 'Tab Title', 'type' => 'text'],
                        ['key' => 'content', 'label' => 'Content (HTML)', 'type' => 'textarea'],
                        ['key' => 'icon',    'label' => 'Icon (Lucide)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['style' => 'underline', 'tabs' => []],
        ],

        // 10. Container / Background Wrapper
        'container' => [
            'label' => 'Container / Wrapper',
            'icon' => 'box',
            'fields' => [
                ['key' => 'html',       'label' => 'Inner Content (HTML)', 'type' => 'textarea'],
                ['key' => 'bg_color',   'label' => 'Background', 'type' => 'select',
                    'options' => ['white' => 'White', 'cream' => 'Cream', 'forest' => 'Forest Green', 'gray' => 'Light Gray', 'dark' => 'Dark']],
                ['key' => 'bg_media_id', 'label' => 'Background Image', 'type' => 'media'],
                ['key' => 'padding',    'label' => 'Padding', 'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large']],
                ['key' => 'rounded',    'label' => 'Rounded Corners', 'type' => 'toggle'],
                ['key' => 'max_width',  'label' => 'Max Width', 'type' => 'select',
                    'options' => ['full' => 'Full', 'xl' => 'Extra Large', 'lg' => 'Large', 'md' => 'Medium']],
            ],
            'defaults' => ['html' => '', 'bg_color' => 'white', 'bg_media_id' => null, 'padding' => 'md', 'rounded' => false, 'max_width' => 'full'],
        ],

        // 11. Spacer
        'spacer' => [
            'label' => 'Spacer',
            'icon' => 'space',
            'fields' => [
                ['key' => 'height', 'label' => 'Height', 'type' => 'select',
                    'options' => ['xs' => 'Extra Small (16px)', 'sm' => 'Small (32px)', 'md' => 'Medium (48px)', 'lg' => 'Large (64px)', 'xl' => 'Extra Large (96px)', '2xl' => 'Huge (128px)']],
                ['key' => 'show_on_mobile', 'label' => 'Show on Mobile', 'type' => 'toggle'],
            ],
            'defaults' => ['height' => 'md', 'show_on_mobile' => true],
        ],

        // 12. Divider
        'divider' => [
            'label' => 'Divider',
            'icon' => 'minus',
            'fields' => [
                ['key' => 'style',  'label' => 'Style', 'type' => 'select',
                    'options' => ['line' => 'Line', 'dashed' => 'Dashed', 'thick' => 'Thick', 'decorative' => 'Decorative', 'dots' => 'Dots', 'leaf' => 'Leaf Icon']],
                ['key' => 'spacing', 'label' => 'Spacing', 'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large']],
            ],
            'defaults' => ['style' => 'line', 'spacing' => 'md'],
        ],

        // =====================================================================
        // MEDIA & VISUAL
        // =====================================================================

        // 13. Image
        'image' => [
            'label' => 'Image',
            'icon' => 'image',
            'fields' => [
                ['key' => 'media_id',  'label' => 'Image',   'type' => 'media'],
                ['key' => 'alt',       'label' => 'Alt Text', 'type' => 'text'],
                ['key' => 'caption',   'label' => 'Caption',  'type' => 'text'],
                ['key' => 'width',     'label' => 'Width',    'type' => 'select',
                    'options' => ['full' => 'Full Width', 'large' => 'Large', 'medium' => 'Medium', 'small' => 'Small']],
            ],
            'defaults' => ['media_id' => null, 'alt' => '', 'caption' => '', 'width' => 'full'],
        ],

        // 14. Image Carousel
        'carousel' => [
            'label' => 'Image Carousel',
            'icon' => 'images',
            'fields' => [
                ['key' => 'slides', 'label' => 'Slides', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'media_id', 'label' => 'Image',   'type' => 'media'],
                        ['key' => 'caption',  'label' => 'Caption', 'type' => 'text'],
                    ]],
                ['key' => 'autoplay',    'label' => 'Autoplay',    'type' => 'toggle'],
                ['key' => 'show_dots',   'label' => 'Show Dots',   'type' => 'toggle'],
                ['key' => 'show_arrows', 'label' => 'Show Arrows', 'type' => 'toggle'],
            ],
            'defaults' => ['slides' => [], 'autoplay' => true, 'show_dots' => true, 'show_arrows' => true],
        ],

        // 15. Gallery Grid
        'gallery' => [
            'label' => 'Gallery Grid',
            'icon' => 'grid-3x3',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'columns', 'label' => 'Columns', 'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4']],
                ['key' => 'aspect',  'label' => 'Aspect Ratio', 'type' => 'select',
                    'options' => ['square' => 'Square', 'landscape' => 'Landscape (4:3)', 'portrait' => 'Portrait (3:4)', 'auto' => 'Auto']],
                ['key' => 'gap',     'label' => 'Gap', 'type' => 'select',
                    'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium']],
                ['key' => 'images',  'label' => 'Images', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'media_id', 'label' => 'Image',   'type' => 'media'],
                        ['key' => 'alt',      'label' => 'Alt Text', 'type' => 'text'],
                        ['key' => 'caption',  'label' => 'Caption',  'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'columns' => '3', 'aspect' => 'square', 'gap' => 'md', 'images' => []],
        ],

        // 16. Before / After Comparison
        'before_after' => [
            'label' => 'Before / After',
            'icon' => 'arrow-left-right',
            'fields' => [
                ['key' => 'before_media_id', 'label' => 'Before Image',   'type' => 'media'],
                ['key' => 'after_media_id',  'label' => 'After Image',    'type' => 'media'],
                ['key' => 'before_label',    'label' => 'Before Label',   'type' => 'text'],
                ['key' => 'after_label',     'label' => 'After Label',    'type' => 'text'],
                ['key' => 'caption',         'label' => 'Caption',        'type' => 'text'],
            ],
            'defaults' => ['before_media_id' => null, 'after_media_id' => null, 'before_label' => 'Before', 'after_label' => 'After', 'caption' => ''],
        ],

        // 17. Image + Text (side by side)
        'image_text' => [
            'label' => 'Image + Text',
            'icon' => 'panel-left',
            'fields' => [
                ['key' => 'media_id',    'label' => 'Image',            'type' => 'media'],
                ['key' => 'heading',     'label' => 'Heading',          'type' => 'text'],
                ['key' => 'text',        'label' => 'Text (HTML)',      'type' => 'textarea'],
                ['key' => 'button_text', 'label' => 'Button Text',     'type' => 'text'],
                ['key' => 'button_url',  'label' => 'Button URL',      'type' => 'text'],
                ['key' => 'image_side',  'label' => 'Image Position',  'type' => 'select',
                    'options' => ['left' => 'Left', 'right' => 'Right']],
                ['key' => 'style',       'label' => 'Style', 'type' => 'select',
                    'options' => ['standard' => 'Standard', 'overlap' => 'Overlap', 'rounded' => 'Rounded']],
            ],
            'defaults' => ['media_id' => null, 'heading' => '', 'text' => '', 'button_text' => '', 'button_url' => '', 'image_side' => 'left', 'style' => 'standard'],
        ],

        // 18. Video Embed
        'video_embed' => [
            'label' => 'Video Embed',
            'icon' => 'play-circle',
            'fields' => [
                ['key' => 'url',     'label' => 'YouTube / Vimeo URL', 'type' => 'text'],
                ['key' => 'caption', 'label' => 'Caption',             'type' => 'text'],
                ['key' => 'aspect',  'label' => 'Aspect Ratio',        'type' => 'select',
                    'options' => ['16:9' => '16:9', '4:3' => '4:3', '1:1' => '1:1']],
            ],
            'defaults' => ['url' => '', 'caption' => '', 'aspect' => '16:9'],
        ],

        // 19. Icon Grid
        'icon_grid' => [
            'label' => 'Icon Grid',
            'icon' => 'shapes',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'columns', 'label' => 'Columns', 'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4', '6' => '6']],
                ['key' => 'style',   'label' => 'Style', 'type' => 'select',
                    'options' => ['minimal' => 'Minimal', 'card' => 'Card', 'circle' => 'Circle Icon']],
                ['key' => 'items',   'label' => 'Items', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon',        'label' => 'Icon (Lucide name)', 'type' => 'text'],
                        ['key' => 'title',       'label' => 'Title',              'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description',        'type' => 'textarea'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'columns' => '3', 'style' => 'card', 'items' => []],
        ],

        // 20. Logo Grid / Partner Logos
        'logo_grid' => [
            'label' => 'Logo Grid',
            'icon' => 'award',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading',  'type' => 'text'],
                ['key' => 'columns', 'label' => 'Columns',  'type' => 'select',
                    'options' => ['3' => '3', '4' => '4', '5' => '5', '6' => '6']],
                ['key' => 'grayscale', 'label' => 'Grayscale (color on hover)', 'type' => 'toggle'],
                ['key' => 'logos',   'label' => 'Logos', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'media_id', 'label' => 'Logo Image', 'type' => 'media'],
                        ['key' => 'name',     'label' => 'Name',       'type' => 'text'],
                        ['key' => 'url',      'label' => 'Link URL',   'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'columns' => '4', 'grayscale' => true, 'logos' => []],
        ],

        // =====================================================================
        // INTERACTIVE & DATA
        // =====================================================================

        // 21. Accordion / FAQ
        'accordion' => [
            'label' => 'Accordion / FAQ',
            'icon' => 'chevrons-up-down',
            'fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'items',   'label' => 'Items', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'question', 'label' => 'Question / Title', 'type' => 'text'],
                        ['key' => 'answer',   'label' => 'Answer / Body',    'type' => 'textarea'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'items' => []],
        ],

        // 22. Cards Grid
        'cards_grid' => [
            'label' => 'Cards Grid',
            'icon' => 'layout-grid',
            'fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'columns', 'label' => 'Columns', 'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4']],
                ['key' => 'cards',   'label' => 'Cards', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon',        'label' => 'Icon (Lucide name)', 'type' => 'text'],
                        ['key' => 'media_id',    'label' => 'Image', 'type' => 'media'],
                        ['key' => 'title',       'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                        ['key' => 'link_url',    'label' => 'Link URL', 'type' => 'text'],
                        ['key' => 'link_text',   'label' => 'Link Text', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'columns' => '3', 'cards' => []],
        ],

        // 23. List
        'list' => [
            'label' => 'List',
            'icon' => 'list',
            'fields' => [
                ['key' => 'style',   'label' => 'Style', 'type' => 'select',
                    'options' => ['bullet' => 'Bullet', 'numbered' => 'Numbered', 'check' => 'Checkmarks', 'icon' => 'Icons']],
                ['key' => 'columns', 'label' => 'Columns', 'type' => 'select',
                    'options' => ['1' => '1', '2' => '2', '3' => '3']],
                ['key' => 'items',   'label' => 'Items', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'text', 'label' => 'Item Text', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['style' => 'bullet', 'columns' => '1', 'items' => []],
        ],

        // 24. Table
        'table' => [
            'label' => 'Table',
            'icon' => 'table',
            'fields' => [
                ['key' => 'caption', 'label' => 'Caption', 'type' => 'text'],
                ['key' => 'headers', 'label' => 'Headers (comma-separated)', 'type' => 'text'],
                ['key' => 'rows',    'label' => 'Rows', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'cells', 'label' => 'Cells (comma-separated)', 'type' => 'text'],
                    ]],
                ['key' => 'striped', 'label' => 'Striped Rows', 'type' => 'toggle'],
            ],
            'defaults' => ['caption' => '', 'headers' => '', 'rows' => [], 'striped' => true],
        ],

        // 25. Stats Row
        'stats_row' => [
            'label' => 'Stats Row',
            'icon' => 'bar-chart-2',
            'fields' => [
                ['key' => 'bg', 'label' => 'Background', 'type' => 'select',
                    'options' => ['forest' => 'Forest Green', 'cream' => 'Cream', 'white' => 'White', 'dark' => 'Dark']],
                ['key' => 'stats', 'label' => 'Stats', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'value',  'label' => 'Value (e.g. 500+)', 'type' => 'text'],
                        ['key' => 'label',  'label' => 'Label',              'type' => 'text'],
                        ['key' => 'icon',   'label' => 'Icon (Lucide)',       'type' => 'text'],
                    ]],
            ],
            'defaults' => ['bg' => 'forest', 'stats' => []],
        ],

        // 26. Pricing Table
        'pricing_table' => [
            'label' => 'Pricing Table',
            'icon' => 'credit-card',
            'fields' => [
                ['key' => 'heading',  'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle',        'type' => 'text'],
                ['key' => 'plans',    'label' => 'Plans',           'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name',        'label' => 'Plan Name',    'type' => 'text'],
                        ['key' => 'price',       'label' => 'Price',        'type' => 'text'],
                        ['key' => 'period',      'label' => 'Period',       'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description',  'type' => 'text'],
                        ['key' => 'features',    'label' => 'Features (one per line)', 'type' => 'textarea'],
                        ['key' => 'button_text', 'label' => 'Button Text',  'type' => 'text'],
                        ['key' => 'button_url',  'label' => 'Button URL',   'type' => 'text'],
                        ['key' => 'highlighted', 'label' => 'Highlight this plan? (yes/no)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'subtitle' => '', 'plans' => []],
        ],

        // 27. Comparison Table
        'comparison_table' => [
            'label' => 'Comparison Table',
            'icon' => 'git-compare',
            'fields' => [
                ['key' => 'heading',    'label' => 'Heading', 'type' => 'text'],
                ['key' => 'col1_title', 'label' => 'Column 1 Title', 'type' => 'text'],
                ['key' => 'col2_title', 'label' => 'Column 2 Title', 'type' => 'text'],
                ['key' => 'rows',       'label' => 'Comparison Rows', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'feature', 'label' => 'Feature / Label', 'type' => 'text'],
                        ['key' => 'col1',    'label' => 'Column 1 Value',  'type' => 'text'],
                        ['key' => 'col2',    'label' => 'Column 2 Value',  'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'col1_title' => '', 'col2_title' => '', 'rows' => []],
        ],

        // 28. Timeline
        'timeline' => [
            'label' => 'Timeline',
            'icon' => 'git-branch',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'items',   'label' => 'Timeline Items', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'date',        'label' => 'Date / Period', 'type' => 'text'],
                        ['key' => 'title',       'label' => 'Title',         'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description',   'type' => 'textarea'],
                        ['key' => 'icon',        'label' => 'Icon (Lucide)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'items' => []],
        ],

        // =====================================================================
        // ENGAGEMENT & CTA
        // =====================================================================

        // 29. CTA Banner
        'cta_banner' => [
            'label' => 'CTA Banner',
            'icon' => 'megaphone',
            'fields' => [
                ['key' => 'heading',      'label' => 'Heading',      'type' => 'text'],
                ['key' => 'subheading',   'label' => 'Subheading',   'type' => 'text'],
                ['key' => 'button_text',  'label' => 'Button Text',  'type' => 'text'],
                ['key' => 'button_url',   'label' => 'Button URL',   'type' => 'text'],
                ['key' => 'style',        'label' => 'Style',        'type' => 'select',
                    'options' => ['forest' => 'Forest Green', 'cream' => 'Cream', 'dark' => 'Dark']],
            ],
            'defaults' => ['heading' => '', 'subheading' => '', 'button_text' => 'Book a Consultation', 'button_url' => '/consultation', 'style' => 'forest'],
        ],

        // 30. Button Group
        'button_group' => [
            'label' => 'Button Group',
            'icon' => 'mouse-pointer-click',
            'fields' => [
                ['key' => 'align', 'label' => 'Alignment', 'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right']],
                ['key' => 'buttons', 'label' => 'Buttons', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'text',   'label' => 'Text',    'type' => 'text'],
                        ['key' => 'url',    'label' => 'URL',     'type' => 'text'],
                        ['key' => 'style',  'label' => 'Style (primary/outline/ghost)', 'type' => 'text'],
                        ['key' => 'icon',   'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'new_tab', 'label' => 'Open in new tab? (yes/no)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['align' => 'left', 'buttons' => []],
        ],

        // 31. Testimonial Card
        'testimonial_card' => [
            'label' => 'Testimonial Card',
            'icon' => 'message-circle',
            'fields' => [
                ['key' => 'quote',      'label' => 'Quote Text',      'type' => 'textarea'],
                ['key' => 'author',     'label' => 'Author Name',     'type' => 'text'],
                ['key' => 'role',       'label' => 'Role / Location', 'type' => 'text'],
                ['key' => 'media_id',   'label' => 'Author Photo',    'type' => 'media'],
                ['key' => 'rating',     'label' => 'Rating (1–5)',    'type' => 'select',
                    'options' => ['5' => '5 Stars', '4' => '4 Stars', '3' => '3 Stars']],
                ['key' => 'style',      'label' => 'Style', 'type' => 'select',
                    'options' => ['card' => 'Card', 'minimal' => 'Minimal', 'featured' => 'Featured/Large']],
            ],
            'defaults' => ['quote' => '', 'author' => '', 'role' => '', 'media_id' => null, 'rating' => '5', 'style' => 'card'],
        ],

        // 32. Team Member
        'team_member' => [
            'label' => 'Team Member',
            'icon' => 'user-circle',
            'fields' => [
                ['key' => 'media_id',  'label' => 'Photo',          'type' => 'media'],
                ['key' => 'name',      'label' => 'Name',           'type' => 'text'],
                ['key' => 'role',      'label' => 'Role / Title',   'type' => 'text'],
                ['key' => 'bio',       'label' => 'Short Bio',      'type' => 'textarea'],
                ['key' => 'phone',     'label' => 'Phone',          'type' => 'text'],
                ['key' => 'email',     'label' => 'Email',          'type' => 'text'],
            ],
            'defaults' => ['media_id' => null, 'name' => '', 'role' => '', 'bio' => '', 'phone' => '', 'email' => ''],
        ],

        // 33. Contact Info
        'contact_info' => [
            'label' => 'Contact Info',
            'icon' => 'phone',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading',      'type' => 'text'],
                ['key' => 'phone',   'label' => 'Phone Number', 'type' => 'text'],
                ['key' => 'email',   'label' => 'Email',        'type' => 'text'],
                ['key' => 'address', 'label' => 'Address',      'type' => 'textarea'],
                ['key' => 'hours',   'label' => 'Business Hours', 'type' => 'textarea'],
                ['key' => 'style',   'label' => 'Layout', 'type' => 'select',
                    'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical', 'card' => 'Card']],
            ],
            'defaults' => ['heading' => '', 'phone' => '', 'email' => '', 'address' => '', 'hours' => '', 'style' => 'horizontal'],
        ],

        // 34. Feature List
        'feature_list' => [
            'label' => 'Feature List',
            'icon' => 'check-square',
            'fields' => [
                ['key' => 'heading',  'label' => 'Heading', 'type' => 'text'],
                ['key' => 'columns',  'label' => 'Columns', 'type' => 'select',
                    'options' => ['1' => '1', '2' => '2']],
                ['key' => 'features', 'label' => 'Features', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon',        'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title',       'label' => 'Title',         'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description',   'type' => 'textarea'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'columns' => '2', 'features' => []],
        ],

        // 35. Steps / Process
        'steps_process' => [
            'label' => 'Steps / Process',
            'icon' => 'list-ordered',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'layout',  'label' => 'Layout',  'type' => 'select',
                    'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical', 'alternating' => 'Alternating']],
                ['key' => 'steps',   'label' => 'Steps',   'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'title',       'label' => 'Title',       'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                        ['key' => 'icon',        'label' => 'Icon (Lucide)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'layout' => 'horizontal', 'steps' => []],
        ],

        // 36. Badge Row
        'badge_row' => [
            'label' => 'Badge / Trust Row',
            'icon' => 'shield-check',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'style',   'label' => 'Style',   'type' => 'select',
                    'options' => ['icon' => 'Icon + Text', 'image' => 'Image Badges', 'pill' => 'Pill Tags']],
                ['key' => 'badges',  'label' => 'Badges',  'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon',     'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'media_id', 'label' => 'Badge Image',   'type' => 'media'],
                        ['key' => 'text',     'label' => 'Text',          'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'style' => 'icon', 'badges' => []],
        ],

        // =====================================================================
        // SPECIALIZED / INDUSTRY
        // =====================================================================

        // 37. Hero Banner (mini)
        'hero_banner' => [
            'label' => 'Hero Banner',
            'icon' => 'monitor',
            'fields' => [
                ['key' => 'heading',     'label' => 'Heading',            'type' => 'text'],
                ['key' => 'subtitle',    'label' => 'Subtitle',           'type' => 'textarea'],
                ['key' => 'media_id',    'label' => 'Background Image',   'type' => 'media'],
                ['key' => 'button_text', 'label' => 'Button Text',        'type' => 'text'],
                ['key' => 'button_url',  'label' => 'Button URL',         'type' => 'text'],
                ['key' => 'overlay',     'label' => 'Overlay Darkness',   'type' => 'select',
                    'options' => ['light' => 'Light', 'medium' => 'Medium', 'dark' => 'Dark']],
                ['key' => 'height',      'label' => 'Height', 'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Full Screen']],
                ['key' => 'align',       'label' => 'Text Alignment', 'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center']],
            ],
            'defaults' => ['heading' => '', 'subtitle' => '', 'media_id' => null, 'button_text' => '', 'button_url' => '', 'overlay' => 'medium', 'height' => 'md', 'align' => 'center'],
        ],

        // 38. Service Highlight Card
        'service_highlight' => [
            'label' => 'Service Highlight',
            'icon' => 'star',
            'fields' => [
                ['key' => 'media_id',    'label' => 'Service Image', 'type' => 'media'],
                ['key' => 'heading',     'label' => 'Service Name',  'type' => 'text'],
                ['key' => 'description', 'label' => 'Description',   'type' => 'textarea'],
                ['key' => 'features',    'label' => 'Key Features (one per line)', 'type' => 'textarea'],
                ['key' => 'button_text', 'label' => 'Button Text',   'type' => 'text'],
                ['key' => 'button_url',  'label' => 'Button URL',    'type' => 'text'],
                ['key' => 'layout',      'label' => 'Layout', 'type' => 'select',
                    'options' => ['card' => 'Card', 'wide' => 'Wide Banner', 'minimal' => 'Minimal']],
            ],
            'defaults' => ['media_id' => null, 'heading' => '', 'description' => '', 'features' => '', 'button_text' => 'Learn More', 'button_url' => '', 'layout' => 'card'],
        ],

        // 39. Project Showcase
        'project_showcase' => [
            'label' => 'Project Showcase',
            'icon' => 'briefcase',
            'fields' => [
                ['key' => 'media_id',    'label' => 'Project Image',   'type' => 'media'],
                ['key' => 'title',       'label' => 'Project Title',   'type' => 'text'],
                ['key' => 'category',    'label' => 'Category/Type',   'type' => 'text'],
                ['key' => 'description', 'label' => 'Description',     'type' => 'textarea'],
                ['key' => 'details',     'label' => 'Details', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'label', 'label' => 'Label (e.g. Duration)', 'type' => 'text'],
                        ['key' => 'value', 'label' => 'Value',                  'type' => 'text'],
                    ]],
                ['key' => 'link_url',    'label' => 'View Project URL', 'type' => 'text'],
            ],
            'defaults' => ['media_id' => null, 'title' => '', 'category' => '', 'description' => '', 'details' => [], 'link_url' => ''],
        ],

        // 40. Seasonal Info / Tips
        'seasonal_info' => [
            'label' => 'Seasonal Info',
            'icon' => 'sun',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'seasons', 'label' => 'Seasons', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'season',      'label' => 'Season (Spring/Summer/Fall/Winter)', 'type' => 'text'],
                        ['key' => 'icon',        'label' => 'Icon (Lucide)',    'type' => 'text'],
                        ['key' => 'title',       'label' => 'Title',           'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description',     'type' => 'textarea'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'seasons' => []],
        ],

        // 41. Area Served / Coverage
        'area_served' => [
            'label' => 'Area Served',
            'icon' => 'map-pin',
            'fields' => [
                ['key' => 'heading',     'label' => 'Heading',     'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['key' => 'columns',     'label' => 'Columns', 'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4']],
                ['key' => 'areas', 'label' => 'Areas', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name',   'label' => 'Area/City Name', 'type' => 'text'],
                        ['key' => 'url',    'label' => 'Link URL',       'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'description' => '', 'columns' => '3', 'areas' => []],
        ],

        // 42. Rating Display
        'rating_display' => [
            'label' => 'Rating Display',
            'icon' => 'star',
            'fields' => [
                ['key' => 'rating',       'label' => 'Rating (1.0–5.0)',  'type' => 'text'],
                ['key' => 'total_reviews', 'label' => 'Total Reviews',     'type' => 'text'],
                ['key' => 'source',       'label' => 'Source (e.g. Google)', 'type' => 'text'],
                ['key' => 'text',         'label' => 'Display Text',      'type' => 'text'],
                ['key' => 'style',        'label' => 'Style', 'type' => 'select',
                    'options' => ['inline' => 'Inline', 'card' => 'Card', 'banner' => 'Banner']],
            ],
            'defaults' => ['rating' => '5.0', 'total_reviews' => '', 'source' => 'Google', 'text' => '', 'style' => 'card'],
        ],

        // 43. Number Counter Row
        'number_counter' => [
            'label' => 'Number Counter',
            'icon' => 'hash',
            'fields' => [
                ['key' => 'bg', 'label' => 'Background', 'type' => 'select',
                    'options' => ['white' => 'White', 'cream' => 'Cream', 'forest' => 'Forest', 'dark' => 'Dark']],
                ['key' => 'counters', 'label' => 'Counters', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'target', 'label' => 'Target Number', 'type' => 'text'],
                        ['key' => 'suffix', 'label' => 'Suffix (+, %, k)', 'type' => 'text'],
                        ['key' => 'label',  'label' => 'Label',          'type' => 'text'],
                        ['key' => 'icon',   'label' => 'Icon (Lucide)',  'type' => 'text'],
                    ]],
            ],
            'defaults' => ['bg' => 'white', 'counters' => []],
        ],

        // 44. Progress Bars
        'progress_bars' => [
            'label' => 'Progress Bars',
            'icon' => 'bar-chart',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'bars', 'label' => 'Bars', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'label',   'label' => 'Label',       'type' => 'text'],
                        ['key' => 'percent', 'label' => 'Percentage',  'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'bars' => []],
        ],

        // =====================================================================
        // UTILITY & EMBED
        // =====================================================================

        // 45. Map Embed
        'map_embed' => [
            'label' => 'Map Embed',
            'icon' => 'map',
            'fields' => [
                ['key' => 'embed_url', 'label' => 'Google Maps Embed URL', 'type' => 'text'],
                ['key' => 'height',    'label' => 'Height (px)',            'type' => 'text'],
                ['key' => 'rounded',   'label' => 'Rounded Corners',       'type' => 'toggle'],
            ],
            'defaults' => ['embed_url' => '', 'height' => '400', 'rounded' => true],
        ],

        // 46. Social Links
        'social_links' => [
            'label' => 'Social Links',
            'icon' => 'share-2',
            'fields' => [
                ['key' => 'heading', 'label' => 'Heading',  'type' => 'text'],
                ['key' => 'align',   'label' => 'Alignment', 'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center']],
                ['key' => 'size',    'label' => 'Size', 'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large']],
                ['key' => 'links',   'label' => 'Links', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'platform', 'label' => 'Platform (facebook/instagram/etc.)', 'type' => 'text'],
                        ['key' => 'url',      'label' => 'Profile URL', 'type' => 'text'],
                    ]],
            ],
            'defaults' => ['heading' => '', 'align' => 'center', 'size' => 'md', 'links' => []],
        ],

        // 47. Marquee / Ticker
        'marquee' => [
            'label' => 'Marquee / Ticker',
            'icon' => 'move-right',
            'fields' => [
                ['key' => 'items', 'label' => 'Items', 'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'text',     'label' => 'Text',                    'type' => 'text'],
                        ['key' => 'media_id', 'label' => 'Logo/Image (optional)', 'type' => 'media'],
                    ]],
                ['key' => 'speed',     'label' => 'Speed', 'type' => 'select',
                    'options' => ['slow' => 'Slow', 'normal' => 'Normal', 'fast' => 'Fast']],
                ['key' => 'direction', 'label' => 'Direction', 'type' => 'select',
                    'options' => ['left' => 'Left', 'right' => 'Right']],
            ],
            'defaults' => ['items' => [], 'speed' => 'normal', 'direction' => 'left'],
        ],

        // 48. Notice Bar
        'notice_bar' => [
            'label' => 'Notice Bar',
            'icon' => 'bell',
            'fields' => [
                ['key' => 'text',        'label' => 'Notice Text',    'type' => 'text'],
                ['key' => 'type',        'label' => 'Type',           'type' => 'select',
                    'options' => ['info' => 'Info (Blue)', 'success' => 'Success (Green)', 'warning' => 'Warning (Yellow)', 'promo' => 'Promo (Forest)']],
                ['key' => 'icon',        'label' => 'Icon (Lucide)',  'type' => 'text'],
                ['key' => 'link_text',   'label' => 'Link Text',     'type' => 'text'],
                ['key' => 'link_url',    'label' => 'Link URL',      'type' => 'text'],
                ['key' => 'dismissible', 'label' => 'Dismissible',   'type' => 'toggle'],
            ],
            'defaults' => ['text' => '', 'type' => 'info', 'icon' => '', 'link_text' => '', 'link_url' => '', 'dismissible' => true],
        ],

        // 49. Embed Code (custom HTML/iframe)
        'embed_code' => [
            'label' => 'Embed / Custom HTML',
            'icon' => 'code',
            'fields' => [
                ['key' => 'html',    'label' => 'HTML / Embed Code', 'type' => 'textarea'],
                ['key' => 'caption', 'label' => 'Caption',           'type' => 'text'],
                ['key' => 'max_width', 'label' => 'Max Width', 'type' => 'select',
                    'options' => ['full' => 'Full', 'lg' => 'Large', 'md' => 'Medium', 'sm' => 'Small']],
            ],
            'defaults' => ['html' => '', 'caption' => '', 'max_width' => 'full'],
        ],

        // 50. Newsletter CTA
        'newsletter_cta' => [
            'label' => 'Newsletter CTA',
            'icon' => 'mail',
            'fields' => [
                ['key' => 'heading',      'label' => 'Heading',      'type' => 'text'],
                ['key' => 'description',  'label' => 'Description',  'type' => 'textarea'],
                ['key' => 'button_text',  'label' => 'Button Text',  'type' => 'text'],
                ['key' => 'placeholder',  'label' => 'Input Placeholder', 'type' => 'text'],
                ['key' => 'form_action',  'label' => 'Form Action URL',   'type' => 'text'],
                ['key' => 'bg',           'label' => 'Background', 'type' => 'select',
                    'options' => ['cream' => 'Cream', 'forest' => 'Forest', 'white' => 'White']],
            ],
            'defaults' => ['heading' => '', 'description' => '', 'button_text' => 'Subscribe', 'placeholder' => 'Enter your email', 'form_action' => '', 'bg' => 'cream'],
        ],

        // 51. Interactive Service Area Map
        'interactive_map' => [
            'label' => 'Interactive Service Area Map',
            'icon' => 'map-pin',
            'fields' => [
                ['key' => 'heading',     'label' => 'Section Heading',          'type' => 'text'],
                ['key' => 'description', 'label' => 'Section Description',      'type' => 'textarea'],
                ['key' => 'map_mode',    'label' => 'Map Mode',                 'type' => 'select',
                    'options' => ['all_cities' => 'All Service Cities', 'single_city' => 'Single City + Neighborhoods', 'custom' => 'Custom Markers']],
                ['key' => 'city_slug',   'label' => 'City Slug (single city mode)', 'type' => 'text'],
                ['key' => 'center_lat',  'label' => 'Center Latitude',          'type' => 'text'],
                ['key' => 'center_lng',  'label' => 'Center Longitude',         'type' => 'text'],
                ['key' => 'zoom',        'label' => 'Default Zoom Level (1-18)', 'type' => 'text'],
                ['key' => 'height',      'label' => 'Map Height (px)',          'type' => 'text'],
                ['key' => 'show_chips',  'label' => 'Show Location Filter Chips', 'type' => 'toggle'],
                ['key' => 'marker_color', 'label' => 'Marker Accent Color',    'type' => 'select',
                    'options' => ['forest' => 'Forest Green', 'accent' => 'Gold Accent', 'blue' => 'Blue', 'red' => 'Red']],
                ['key' => 'popup_cta_text', 'label' => 'Popup CTA Button Text', 'type' => 'text'],
                ['key' => 'schema_type', 'label' => 'Schema Type',             'type' => 'select',
                    'options' => ['LocalBusiness' => 'LocalBusiness', 'Place' => 'Place', 'none' => 'No Schema']],
                ['key' => 'markers',     'label' => 'Custom/Override Markers',  'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name',              'label' => 'Location Name',      'type' => 'text'],
                        ['key' => 'lat',               'label' => 'Latitude',           'type' => 'text'],
                        ['key' => 'lng',               'label' => 'Longitude',          'type' => 'text'],
                        ['key' => 'popup_heading',     'label' => 'Popup Heading',      'type' => 'text'],
                        ['key' => 'popup_description', 'label' => 'Popup Description',  'type' => 'textarea'],
                        ['key' => 'popup_cta_text',    'label' => 'CTA Button Text',    'type' => 'text'],
                        ['key' => 'popup_cta_url',     'label' => 'CTA Button URL',     'type' => 'text'],
                        ['key' => 'popup_services',    'label' => 'Services (comma-separated)', 'type' => 'text'],
                    ]],
            ],
            'defaults' => [
                'heading' => '', 'description' => '', 'map_mode' => 'all_cities',
                'city_slug' => '', 'center_lat' => '43.55', 'center_lng' => '-79.65',
                'zoom' => '9', 'height' => '500', 'show_chips' => true,
                'marker_color' => 'forest', 'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness', 'markers' => [],
            ],
        ],

        // 52. Unified Service Area (Map + City Grid combined)
        'service_area' => [
            'label' => 'Service Area (Map + City Grid)',
            'icon' => 'globe',
            'fields' => [
                ['key' => 'heading',     'label' => 'Section Heading',          'type' => 'text'],
                ['key' => 'description', 'label' => 'Section Description',      'type' => 'textarea'],
                ['key' => 'mode',        'label' => 'Display Mode',             'type' => 'select',
                    'options' => ['combined' => 'Map + City Grid', 'map_only' => 'Map Only', 'list_only' => 'City Grid Only', 'embed_only' => 'Embed Only']],
                ['key' => 'map_mode',    'label' => 'Map Data Source',          'type' => 'select',
                    'options' => ['all_cities' => 'All Service Cities', 'single_city' => 'Single City + Neighborhoods']],
                ['key' => 'city_slug',   'label' => 'City Slug (single city mode)', 'type' => 'text'],
                ['key' => 'center_lat',  'label' => 'Center Latitude',          'type' => 'text'],
                ['key' => 'center_lng',  'label' => 'Center Longitude',         'type' => 'text'],
                ['key' => 'zoom',        'label' => 'Default Zoom Level (1-18)', 'type' => 'text'],
                ['key' => 'height',      'label' => 'Map Height (px)',          'type' => 'text'],
                ['key' => 'show_chips',  'label' => 'Show Location Filter Chips', 'type' => 'toggle'],
                ['key' => 'popup_cta_text', 'label' => 'Popup CTA Button Text', 'type' => 'text'],
                ['key' => 'schema_type', 'label' => 'Schema Type',             'type' => 'select',
                    'options' => ['LocalBusiness' => 'LocalBusiness', 'Place' => 'Place', 'none' => 'No Schema']],
                ['key' => 'embed_url',   'label' => 'Google Maps Embed URL (embed_only mode)', 'type' => 'text'],
                ['key' => 'areas',       'label' => 'Custom Area Links (overrides auto-generated)',  'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name', 'label' => 'Area Name', 'type' => 'text'],
                        ['key' => 'url',  'label' => 'Link URL',  'type' => 'text'],
                    ]],
                ['key' => 'markers',     'label' => 'Custom Map Markers',      'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name',              'label' => 'Location Name',     'type' => 'text'],
                        ['key' => 'lat',               'label' => 'Latitude',          'type' => 'text'],
                        ['key' => 'lng',               'label' => 'Longitude',         'type' => 'text'],
                        ['key' => 'popup_heading',     'label' => 'Popup Heading',     'type' => 'text'],
                        ['key' => 'popup_description', 'label' => 'Popup Description', 'type' => 'textarea'],
                        ['key' => 'popup_cta_text',    'label' => 'CTA Button Text',   'type' => 'text'],
                        ['key' => 'popup_cta_url',     'label' => 'CTA Button URL',    'type' => 'text'],
                    ]],
            ],
            'defaults' => [
                'heading' => 'Our Service Areas', 'description' => '', 'mode' => 'combined',
                'map_mode' => 'all_cities', 'city_slug' => '', 'center_lat' => '43.55', 'center_lng' => '-79.65',
                'zoom' => '9', 'height' => '500', 'show_chips' => true,
                'popup_cta_text' => 'Book a Consultation', 'schema_type' => 'LocalBusiness',
                'embed_url' => '', 'areas' => [], 'markers' => [],
            ],
        ],

        // =====================================================================
        // LAYOUT SECTIONS (Legacy / Integrated)
        // =====================================================================

        'hero' => [
            'label' => 'Hero Banner (Layout)',
            'icon' => 'layout',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'service_hero' => [
            'label' => 'Service Hero (Layout)',
            'icon' => 'layout',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'stats_bar' => [
            'label' => 'Trust Stats Bar (Layout)',
            'icon' => 'bar-chart-2',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'services_grid' => [
            'label' => 'Services Grid (Layout)',
            'icon' => 'grid',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'local_about' => [
            'label' => 'About & Neighbourhoods (Layout)',
            'icon' => 'map-pin',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'local_intro' => [
            'label' => 'Local Intro / Answer (Layout)',
            'icon' => 'file-text',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'process_steps' => [
            'label' => 'Our Process (Layout)',
            'icon' => 'list-ordered',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'portfolio_gallery' => [
            'label' => 'Portfolio Gallery (Layout)',
            'icon' => 'image',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'portfolio_preview' => [
            'label' => 'Portfolio Preview (Layout)',
            'icon' => 'images',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'testimonials' => [
            'label' => 'Testimonials (Layout)',
            'icon' => 'star',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'faq_section' => [
            'label' => 'FAQs (Layout)',
            'icon' => 'help-circle',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'trust_badges' => [
            'label' => 'Trust Badges (Layout)',
            'icon' => 'shield-check',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'cta_section' => [
            'label' => 'CTA Banner (Layout)',
            'icon' => 'megaphone',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'benefits_grid' => [
            'label' => 'Why Choose Us (Layout)',
            'icon' => 'award',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'city_grid' => [
            'label' => 'Service Areas Grid (Layout)',
            'icon' => 'map-pin',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'blog_strip' => [
            'label' => 'Blog Strip (Layout)',
            'icon' => 'newspaper',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'service_body' => [
            'label' => 'Service Body (Layout)',
            'icon' => 'file-text',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],
        'city_availability' => [
            'label' => 'Cities Served (Layout)',
            'icon' => 'map-pin',
            'is_layout_section' => true,
            'fields' => [],
            'defaults' => [],
        ],

    ],

];
