<?php wp_enqueue_script( 'forms' ); ?>

<form method="POST" class="form" enctype="multipart/form-data" id="form-create-product">
    <fieldset>
        <div class="form__fields">
            <div class="form-row form-row--one">
                <div class="form-col">
                    <div class="input input--required">
                        <label for="fcp-title"><?=__( 'Product title', 'bdd' ); ?></label>
                        <div class="input__wrap">
                            <input type="text" name="title" placeholder="<?=__( 'Enter title', 'bdd' ); ?>" id="fcp-title">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row form-row--one">
                <div class="form-col">
                    <div class="input input--price input--required">
                        <label for="fcp-price"><?=sprintf( __( 'Price (%s)', 'bdd' ), get_woocommerce_currency_symbol() ); ?></label>
                        <div class="input__wrap">
                            <input type="number" name="price" min="0" placeholder="<?=__( 'Enter price', 'bdd' ); ?>" id="fcp-price">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row form-row--two">
                <div class="form-col">
                    <div class="input input--date">
                        <label for="fcp-date"><?=__( 'Date', 'bdd' ); ?></label>
                        <div class="input__wrap">
                            <input type="date" name="date" value="<?=current_time( 'Y-m-d' ); ?>" id="fcp-date">
                        </div>
                    </div>
                </div>

                <div class="form-col">
                    <div class="input input--select input--required">
                        <label for="fcp-type"><?=__( 'Type', 'bdd' ); ?></label>
                        <div class="input__wrap">
                            <select name="type" id="fcp-type">
                                <option value="" selected><?=__( 'Select type', 'bdd' ); ?></option>
                                <?php foreach( bdd_get_product_types() as $value => $title ) : ?>
                                    <option value="<?=$value; ?>"><?=$title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row form-row--one">
                <div class="form-col">
                    <div class="input input--file">
                        <label for="fcp-thumbnail"><?=sprintf( __( 'Custom thumbnail (%s: %s)', 'bdd' ), __( 'Maximum file size', 'bdd' ), '1mb' ); ?></label>
                        <div class="input__wrap">
                            <input type="file" name="thumbnail" accept="image/png, image/jpeg" id="fcp-thumbnail">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="form__response"></div>

    <div class="form__actions">
        <button type="submit" class="btn btn--default"><?=__( 'Create Product', 'bdd' ); ?></button>
    </div>

    <?php wp_nonce_field('create_product', 'create_product_nonce' ); ?>
    <input type="hidden" name="action" value="create_product">
</form>