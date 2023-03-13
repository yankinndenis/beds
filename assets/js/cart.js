let site_url = document.location.origin;

$("body").on('change','.woocommerce-cart-form .accompanied_dog', function (e) {
    let sum = 0;
    $( ".accompanied_dog" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != 0) {
        Cookies.set('accompanied_dog', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('accompanied_dog');
    }
    
    $('[name="update_cart"]').removeAttr('disabled');
    $('[name="update_cart"]').removeAttr('aria-disabled');
    $('[name="update_cart"]').trigger('click');
});

$("body").on('change','.woocommerce-cart-form .cancellation', function (e) {
    let sum = 0;
    $( ".cancellation" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != 0) {
        Cookies.set('cancellation', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('cancellation');
    }
    
    $('[name="update_cart"]').removeAttr('disabled');
    $('[name="update_cart"]').removeAttr('aria-disabled');
    $('[name="update_cart"]').trigger('click');
});

$("body").on('change','.woocommerce-cart-form .final_cleaning', function (e) {
    let sum = '';
    $( ".final_cleaning" ).each(function() {
        let value = $( this ).val();
        let name = $(this).attr('name');
        value = parseFloat(value);
        
        if ($(this).prop('checked')) {
            Cookies.set(name, value, { expires: 1, path: '/' });
            console.log(name);
            sum = sum+value;
        }else{
            Cookies.remove(name);
            console.log('no');
        }
    });
    if (sum != '') {
        Cookies.set('final_cleaning', sum, { expires: 1, path: '/' });
    }else{
        Cookies.remove('final_cleaning');
    }
    
    $('[name="update_cart"]').removeAttr('disabled');
    $('[name="update_cart"]').removeAttr('aria-disabled');
    $('[name="update_cart"]').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="accompanied_dog"]', function (e) {
    let value = $(this).val();
    
    if ($(this).prop('checked')) {
        Cookies.set('accompanied_dog', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('accompanied_dog');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="cancellation"]', function (e) {
    let value = $(this).val();
    
    if ($(this).prop('checked')) {
        Cookies.set('cancellation', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('cancellation');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});

$("body").on('change','.woocommerce-checkout [name="final_cleaning"]', function (e) {
    let value = $(this).val();
    
    if ($(this).prop('checked')) {
        Cookies.set('final_cleaning', value, { expires: 1, path: '/' });
    }else{
        Cookies.remove('final_cleaning');
    }
    $('#ship-to-different-address-checkbox').trigger('click');
    $('#ship-to-different-address-checkbox').trigger('click');
});