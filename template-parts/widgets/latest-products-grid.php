<?php

$loop = bdd_query_products();

if( $loop->have_posts() ) : ?>
    <ul class="products-grid products-grid--latest">
        <?php while( $loop->have_posts() ) : ?>
            <?php
            
            $loop->the_post();
            wc_get_template_part( 'content', 'product' );
                
            ?>
        <?php endwhile; ?>
    </ul>
<?php else : ?>
    <h2 align="center"><?=__( 'No products', 'bdd' ); ?></h2>
<?php endif;

wp_reset_postdata();