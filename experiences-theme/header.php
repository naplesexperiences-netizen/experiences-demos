<?php
/**
 * Header template — Experiences Srl
 * @package experiences-srl
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ① Tailwind CDN — must be first so config runs before body renders -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   '#0B3D61',
                        secondary: '#0D7C7C',
                        accent:    '#14A3A3',
                        dark:      '#0A1628',
                        light:     '#E8F4F4'
                    },
                    fontFamily: {
                        sans:    ['Inter', 'sans-serif'],
                        heading: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- ② Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- ③ Font Awesome — loaded directly to avoid WP reorder issues -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- ④ AOS animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- ⑤ WP head (theme stylesheet + any plugin styles) -->
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50 font-sans text-gray-800 overflow-x-hidden'); ?>>
<?php wp_body_open(); ?>
