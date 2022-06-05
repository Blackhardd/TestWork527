jQuery(document).ready(function($){
    $('a[data-action="upload_image"]').on('click', function(e){
        e.preventDefault()

        const $button = $(this)
        const uploader = wp.media({
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function(){
            const attachment = uploader.state().get('selection').first().toJSON()
            $button.html('<img src="' + attachment.sizes.thumbnail.url + '" />').next().show().next().val(attachment.id)
        }).open()
    })

    $('a[data-action="remove_image"]').on('click', function(e){
        e.preventDefault()

        const $button = $(this)
        $button.next().val('')
        $button.prev().html(bdd_woocommerce_i18n.upload_image)
        $button.hide()
    })
})