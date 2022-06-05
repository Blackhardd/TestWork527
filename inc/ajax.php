<?php

$public_actions = array(
    'create_product'
);

foreach( $public_actions as $action ) :
    add_action( "wp_ajax_{$action}", "bdd_ajax_{$action}" );
    add_action( "wp_ajax_nopriv_{$action}", "bdd_ajax_{$action}" );
endforeach;


function bdd_ajax_create_product(){
    if( empty( $_POST['create_product_nonce'] ) || !wp_verify_nonce( $_POST['create_product_nonce'], 'create_product' ) )
        bdd_ajax_response( false, __( 'Something went wrong', 'bdd' ) );

    $thumbnail = null;

    // To refactor: move file uploading to separate function
    if( $_FILES['thumbnail'] && $_FILES['thumbnail']['size'] !== 0 ){
        $file_name = str_replace( ' ', '-', $_FILES['thumbnail']['name'] );
        $file_data = wp_upload_bits( $file_name, null, file_get_contents( $_FILES['thumbnail']['tmp_name'] ) );

        if( $file_data ){
            $attachment = array(
                'guid'              => $file_data['url'],
                'post_mime_type'    => $file_data['type'],
                'post_title'        => $file_data['file'],
                'post_content'      => '',
                'post_status'       => 'inherit'
            );

            $attachment_id = wp_insert_attachment( $attachment, $file_data['file'] );

            if( $attachment_id ){
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                $attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_data['file'] );
                wp_update_attachment_metadata( $attachment_id, $attachment_data );

                $thumbnail = $attachment_id;
            }
        }
    }

    $product = bdd_create_product( $_POST['title'], $_POST['price'], $_POST['date'], $_POST['type'], $thumbnail );

    $product ? bdd_ajax_response( true, __( 'Product is created successfully', 'bdd' ) ) : bdd_ajax_response( false, __( 'Something went wrong', 'bdd' ) );
}