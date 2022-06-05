<?php

if( !defined( 'BDD_THEME_VERSION' ) )
    define( 'BDD_THEME_VERSION', '1.0.0' );

if( !defined( 'BDD_THEME_PATH' ) )
    define( 'BDD_THEME_PATH', get_template_directory() );

if( !defined( 'BDD_THEME_URI' ) )
    define( 'BDD_THEME_URI', get_template_directory_uri() );


/**
 *  Setting up theme.
 */

if( !function_exists( 'bdd_theme_setup' ) ){
    function bdd_theme_setup(){
        add_theme_support( 'title-tag' );

        register_nav_menus( array(
            'primary-menu' => __( 'Primary Menu', 'bdd' )
        ) );
    }

    bdd_theme_setup();
}


/**
 *  Includes
 */

require_once( BDD_THEME_PATH . '/inc/framework.php' );
require_once( BDD_THEME_PATH . '/inc/ajax.php' );


/**
 *  Enqueue theme scripts and styles.
 */

add_action( 'wp_enqueue_scripts', 'bdd_enqueue_scripts' );

function bdd_enqueue_scripts(){
    wp_enqueue_style( 'theme', get_stylesheet_uri(), array(), BDD_THEME_VERSION );

    wp_register_script( 'forms', BDD_THEME_URI . '/assets/js/front/forms.js', array( 'jquery' ), BDD_THEME_VERSION, true );

    wp_localize_script( 'forms', 'bdd_forms_data', array(
        'ajax_url'              => admin_url( 'admin-ajax.php' )
    ) );

    wp_localize_script( 'forms', 'bdd_forms_i18n', array(
        'required_field'        => __( 'Required field', 'bdd' ),
        'file_is_too_large'     => __( 'File size is too large', 'bdd' )
    ) );
}


/**
 *  Enqueue theme admin scripts and styles.
 */

add_action( 'admin_enqueue_scripts', 'bdd_enqueue_admin_scripts' );

function bdd_enqueue_admin_scripts(){
    wp_register_script( 'bdd-woocommerce-image-uploader-field', BDD_THEME_URI . '/assets/js/back/woocommerce/custom-fields/image-uploader.js', array( 'jquery' ), BDD_THEME_VERSION, true );

    wp_localize_script( 'bdd-woocommerce-image-uploader-field', 'bdd_woocommerce_i18n', array(
        'upload_image' => __( 'Upload image', 'bdd' )
    ) );
}


/**
 *  Printing script to product page on admin side.
 */

add_action( 'admin_footer', 'bdd_print_admin_product_script' );

function bdd_print_admin_product_script(){
    if( bdd_is_woocommerce_product_edit_page() ) : ?>
        <script>
            jQuery(document).ready(function($){
                $('.button[data-action="clear_custom_fields"]').on('click', function(){
                    $('#_custom_image').val('').prev().hide().prev().html('<?=__( 'Upload image', 'bdd' ); ?>')
                    $('#_date_created').val('')
                    $('#_product_type').val($("#_product_type option:first").val())
                })

                $('.button[data-action="submit_product_update"]').on('click', function(){
                    $('#post').submit()
                })
            })
        </script>
    <?php endif;
}


/**
 *  Adding custom fields to products.
 */

add_action( 'woocommerce_product_options_general_product_data', 'bdd_woocommerce_product_custom_fields' );

function bdd_woocommerce_product_custom_fields(){
    global $post;

    $custom_image = get_post_meta( $post->ID, '_custom_thumbnail', true );
    $date_created = get_post_meta( $post->ID, '_date_created', true );
    $product_type_value = get_post_meta( $post->ID, '_product_type', true );

    echo '<div class="custom-fields">';

    bdd_woocommerce_wp_image_upload( array(
        'id'        => '_custom_thumbnail',
        'label'     => __( 'Custom image', 'bdd' ),
        'value'     => $custom_image
    ) );

    woocommerce_wp_text_input( array(
        'id'        => '_date_created',
        'label'     => __( 'Date of creation', 'bdd' ),
        'type'      => 'date',
        'value'     => $date_created
    ) );

    woocommerce_wp_select( array(
        'id'        => '_product_type',
        'label'     => __( 'Product type', 'bdd' ),
        'options'   => array( '' => __( 'Select value', 'bdd' ) ) + bdd_get_product_types(),
        'value'     => $product_type_value
    ) );

    echo '</div>';

    ?>

    <div class="custom-actions" style="padding-left: 12px;">
        <button type="button" class="button button-primary" data-action="clear_custom_fields"><?=__( 'Clear custom fields', 'bdd' ); ?></button>
        <button type="button" class="button button-primary" data-action="submit_product_update"><?=__( 'Update product', 'bdd' ); ?></button>
    </div>

    <?php
}


/**
 *  Saving custom fields to product meta.
 */

add_action( 'woocommerce_process_product_meta', 'bdd_woocommerce_product_custom_fields_save' );

function bdd_woocommerce_product_custom_fields_save( $post_id ){
    $fields = array(
        '_custom_thumbnail' => $_POST['_custom_thumbnail'],
        '_date_created'     => $_POST['_date_created'],
        '_product_type'     => $_POST['_product_type']
    );

    foreach( $fields as $key => $value ) :
        if( $value ) :
            update_post_meta( $post_id, $key, sanitize_text_field( $value ) );
        else:
            update_post_meta( $post_id, $key, '' );
        endif;
    endforeach;
}


/**
 *  Removing default product thumbnail column and adding custom image display column.
 */

add_filter( 'manage_edit-product_columns', 'bdd_remove_product_thunbnail_column' );

function bdd_remove_product_thunbnail_column( $columns ){
    unset( $columns['thumb'] );

    return array_slice( $columns, 0, 1, true ) + array( 'custom_thumb' => sprintf( '<span style="white-space: nowrap;">%s</span>', __( 'Thumb', 'bdd' ) ) ) + array_slice( $columns, 1, null, true );
}


/**
 *  Displaying custom image on related column.
 */

add_filter( 'manage_posts_custom_column', 'bdd_filter_posts_custom_column' );

function bdd_filter_posts_custom_column( $name ){
    if( $name === 'custom_thumb' ){
        $post_id = get_the_ID();
        $edit_post_link = get_edit_post_link( $post_id );
        ?>
            <a href="<?=$edit_post_link; ?>">
                <?php if( $custom_image_id = get_post_meta( $post_id, '_custom_thumbnail', true ) ) : ?>
                    <?=wp_get_attachment_image( $custom_image_id, 'thumbnail', false, array( 'style' => 'width: 40px; height: 40px;' ) ); ?>
                <?php else : ?>
                    <?=wc_placeholder_img( 'thumbnail', array( 'style' => 'width: 40px; height: 40px;' ) ); ?>
                <?php endif; ?>
            </a>
        <?php
    }
}


/**
 *  Replacing loop product thumbnail to display custom thumbnail.
 */

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'bdd_woocommerce_template_loop_product_custom_thumbnail', 10 );

function bdd_woocommerce_template_loop_product_custom_thumbnail(){
    global $product;

    if( $thumbnail_id = $product->get_meta( '_custom_thumbnail' ) ){
        echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail' );
    }
    else{
        echo wc_placeholder_img();
    }
}