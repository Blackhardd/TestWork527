<?php get_header(); ?>

<div class="container">
    <?php

    if( have_posts() ) :
        while( have_posts() ) :
            the_post();
        endwhile;
    else:
        echo __( 'No posts', 'bdd' );
    endif;

    ?>
</div>

<?php get_footer(); ?>