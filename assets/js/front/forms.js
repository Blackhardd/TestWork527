jQuery(document).ready(function($){
    const input_selectors = 'input[type="text"],input[type="date"],input[type="number"],input[type="file"],select'

    const $forms = $('form.form')

    $forms.on('submit', function(e){
        e.preventDefault()

        if(validate_form($(this))) submit_form($(this))

        return false
    })

    function validate_form($form){
        if(!$form) return

        let is_form_valid = true

        const $inputs = $form.find(input_selectors)

        $inputs.each(function(){
            const $field = $(this)
            const is_field_valid = validate_input($field)

            if(!is_field_valid && is_form_valid) is_form_valid = false
        })

        return is_form_valid
    }

    function display_input_error($input, error){
        if(!$input || !error) return

        const $wrapper = $input.closest('.input')

        $wrapper.addClass('input--not-valid')

        $wrapper.find('.input-error').remove()
        $wrapper.append('<div class="input-error input__error">' + error + '</div>')

        $input.one('input', function(e){
            $wrapper.removeClass('input--not-valid')
            $wrapper.find('.input-error').remove()
        })
    }

    function validate_input($input){
        switch($input.attr('type')){
            case 'file':
                return validate_input_image($input)
            default:
                return validate_input_required($input)
        }
    }

    function validate_input_required($input){
        if($input.closest('.input').hasClass('input--required') && !$input.val()){
            display_input_error($input, bdd_forms_i18n.required_field)

            return false
        }

        return true
    }

    function validate_input_image($input){
        if($input[0].files[0] && $input[0].files[0].size > 1048576){
            display_input_error($input, bdd_forms_i18n.file_is_too_large)

            return false
        }

        return true
    }

    function submit_form($form){
        if(!$form) return

        const fd = new FormData($form[0])

        $form.find('fieldset,button[type="submit"]').attr('disabled', true)

        $.ajax({
            method: 'POST',
            url: bdd_forms_data.ajax_url,
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                const res = JSON.parse(response)

                if(res.status === true){
                    $form.addClass('form--success')
                    $form.trigger('reset')
                }
                else{
                    $form.addClass('form--failure')
                }
                
                $form.find('fieldset,button[type="submit"]').removeAttr('disabled')
                $form.find('.form__response').html(res.message)
            }
        })
    }
})