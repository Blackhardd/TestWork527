<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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