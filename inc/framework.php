<?php

/**
 * @return array[]
 */

function bdd_get_product_types(){
    return array(
        'frequent'  => __( 'Frequent', 'bdd' ),
        'unusual'   => __( 'Unusual', 'bdd' ),
        'rare'      => __( 'Rare', 'bdd' ),
    );
}

/**
 * @param int $size
 * @return WP_Query object
 */

function bdd_query_products( $size = 12 ){
    return new WP_Query( array(
        'post_type'         => 'product',
        'posts_per_page'    => $size
    ) );
}


/**
 * @param string $title
 * @param int|string $price
 * @param string $date
 * @param string $type
 */

function bdd_create_product( $title, $price, $date, $type, $thumbnail = null ){
    if( !$title || !$price || !$date || !$type )
        return;

    $product = new WC_Product_Simple();
    
    $product->set_name( $title );
    $product->update_meta_data( '_date_created', $date );
    $product->update_meta_data( '_product_type', $type );
    $product->set_regular_price( $price );

    if( $thumbnail ){
        $product->update_meta_data( '_custom_thumbnail', $thumbnail );
    }
    
    $product->save();

    return $product;
}


function bdd_is_woocommerce_product_edit_page(){
    $current_screen = get_current_screen();

    return $current_screen->parent_base === 'edit' && $current_screen->post_type === 'product';
}


/**
 * @params array $params
 */

function bdd_woocommerce_wp_image_upload( $params = array( 'id' => false, 'label' => false, 'value' => false ) ){
    if( !$params['id'] || !$params['label'] )
        return;

    wp_enqueue_script( 'bdd-woocommerce-image-uploader-field' );

    echo "<p class='form-field {$params['id']}_field'>";
    echo "<label for='{$params['id']}'>{$params['label']}</label>";

    if( $image = wp_get_attachment_image_src( $params['value'] ) ){
        echo "<a href='#' data-action='upload_image'><img src='{$image[0]}' /></a>";
        echo "<a href='#' data-action='remove_image'>" . __( 'Remove image', 'bdd' ) . "</a>";
        echo "<input type='hidden' name='{$params['id']}' value='{$params['value']}' id='{$params['id']}'>";
    }
    else{
        echo "<a href='#' data-action='upload_image'>" . __( 'Upload image', 'bdd' ) . "</a>";
        echo "<a href='#' data-action='remove_image' style='display: none;'>" . __( 'Remove image', 'bdd' ) . "</a>";
        echo "<input type='hidden' name='{$params['id']}' id='{$params['id']}'>";
    }

    echo "</p>";
}


/**
 * @param boolean $status
 * @param string $message
 */

function bdd_ajax_response( $status = true, $message = null ){
    wp_die( json_encode( array( 'status' => $status, 'message' => $message ) ) );
}