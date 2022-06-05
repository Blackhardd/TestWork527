<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body>
    <?php get_template_part( 'template-parts/header/default' ); ?>

    <main class="site-content">
        <header class="page-header">
            <div class="container">
                <?php the_title( '<h1 class="page-title">', "</h1>" ); ?>
            </div>
        </header>

        <div class="page-content">