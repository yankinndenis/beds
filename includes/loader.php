<?php



/**

 * connect files

 */

require_once BEDS_DIR . '/includes/ajax.php';

require_once BEDS_DIR . '/includes/shortcodes.php';

require_once BEDS_DIR . '/includes/constants.php';


/**

 * register scripts|styles

 */

add_action('wp_enqueue_scripts', 'beds_register_scripts');

function beds_register_scripts(){
    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', BEDS_URL.'assets/js/jquery-3.6.2.min.js', false, false, true);

    wp_register_style('beds-bootstrap-style' , BEDS_URL.'assets/css/bootstrap.min.css');
    wp_register_style('beds-litepicker-style' , BEDS_URL.'assets/css/litepicker.css');
    wp_register_style('beds-slick-style' , BEDS_URL.'assets/css/slick.css');
    wp_register_style('beds-slick_theme-style' , BEDS_URL.'assets/css/slick-theme.css');
    wp_register_style('beds-checkout-style' , BEDS_URL.'assets/css/checkout_styles.css');
    wp_register_style('beds-register-style' , BEDS_URL.'assets/css/style.css');
    wp_register_style('beds-hotel-style' , BEDS_URL.'assets/css/hotel.css');

    wp_register_script('beds-cookie-script',BEDS_URL.'assets/js/js.cookie.min.js');
    wp_register_script('beds-filters-script',BEDS_URL.'assets/js/filters.js');
    wp_register_script('beds-buttons-script', BEDS_URL.'assets/js/buttons.js', array('jquery'));
    wp_register_script('beds-moment-script', BEDS_URL.'assets/js/moment.min.js', array('jquery'));
    wp_register_script('beds-litepicker-script', BEDS_URL.'assets/js/litepicker.js', array('jquery'));
    wp_register_script('beds-slick-script', BEDS_URL.'assets/js/slick.min.js', array('jquery'));
    wp_register_script('beds-fslightbox-script', BEDS_URL.'assets/js/fslightbox.js', array('jquery'));
    wp_register_script('beds-hotel_inner-script', BEDS_URL.'assets/js/hotel_inner.js', array('jquery'));
    wp_register_script('beds-product-script', BEDS_URL.'assets/js/product.js', array('jquery'));
    wp_register_script('beds-register-script', BEDS_URL.'assets/js/script.js', array('jquery'));
    wp_register_script('beds-cart-script', BEDS_URL.'assets/js/cart.js', array('jquery'));
    wp_localize_script('beds-register-script', 'beds_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
}

// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
function woocommerce_product_custom_fields(){
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    //Custom Product Number Field
    woocommerce_wp_text_input( 
        array(
            'id'        => '_product_breadcrumbs',
            'desc'      => __('Breadcrumbs', 'woocommerce'),
            'label'     => __('Breadcrumbs', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_text_input(
        array(
            'id' => '_product_beds_id',
            'placeholder' => 'Beds id',
            'label' => __('Beds id', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    //Custom Product Number Field
    woocommerce_wp_text_input(
        array(
            'id' => '_product_peoples',
            'placeholder' => 'Peoples',
            'label' => __('Peoples', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '0'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_sovrum',
            'placeholder' => 'Sovrum',
            'label' => __('Sovrum', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '1'
            )
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => '_product_skidlift',
            'placeholder' => 'Skidlift',
            'label' => __('Skidlift', 'woocommerce'),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min' => '1'
            )
        )
    );
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_hundtillåtet',
            'desc'      => __('Hundtillåtet', 'woocommerce'),
            'label'     => __('Hundtillåtet', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_wi_fi',
            'desc'      => __('WI-FI', 'woocommerce'),
            'label'     => __('WI-FI', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_bastu',
            'desc'      => __('Bastu', 'woocommerce'),
            'label'     => __('Bastu', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_oppen_spis',
            'desc'      => __('Öppen spis', 'woocommerce'),
            'label'     => __('Öppen spis', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_skidförråd',
            'desc'      => __('Skidförråd', 'woocommerce'),
            'label'     => __('Skidförråd', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_diskmaskin',
            'desc'      => __('Diskmaskin', 'woocommerce'),
            'label'     => __('Diskmaskin', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_tvättmaskin',
            'desc'      => __('Tvättmaskin', 'woocommerce'),
            'label'     => __('Tvättmaskin', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_torkskåp',
            'desc'      => __('Torkskåp', 'woocommerce'),
            'label'     => __('Torkskåp', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_barnstol',
            'desc'      => __('Barnstol', 'woocommerce'),
            'label'     => __('Barnstol', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    woocommerce_wp_checkbox( 
        array(
            'id'        => '_product_barnsäng',
            'desc'      => __('Barnsäng', 'woocommerce'),
            'label'     => __('Barnsäng', 'woocommerce'),
            'desc_tip'  => 'true'
    ));
    
    echo '</div>';
}

// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id){
    $woocommerce_custom_product_number_field = $_POST['_product_beds_id'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_beds_id', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_peoples'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_peoples', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_sovrum'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_sovrum', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_skidlift'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_skidlift', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_hundtillåtet'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_hundtillåtet', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_wi_fi'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_wi_fi', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_bastu'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_bastu', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_oppen_spis'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_oppen_spis', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_skidförråd'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_skidförråd', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_diskmaskin'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_diskmaskin', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_tvättmaskin'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_tvättmaskin', esc_attr($woocommerce_custom_product_number_field));
    }   
    $woocommerce_custom_product_number_field = $_POST['_product_torkskåp'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_torkskåp', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_barnstol'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_barnstol', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_barnsäng'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_barnsäng', esc_attr($woocommerce_custom_product_number_field));
    }
    $woocommerce_custom_product_number_field = $_POST['_product_breadcrumbs'];
    if (!empty($woocommerce_custom_product_number_field)){
        update_post_meta($post_id, '_product_breadcrumbs', esc_attr($woocommerce_custom_product_number_field));
    }
}

add_filter( 'cron_schedules', 'every_30_minutes' );
function every_30_minutes( $schedules ) {
    $schedules['every_30'] = array(
            'interval'  => 60 * 30,
            'display'   => __( 'Every 30 Minutes', 'textdomain' )
    );
    return $schedules;
}

add_filter( 'cron_schedules', 'every_10_second' );
function every_10_second( $schedules ) {
    $schedules['ten_sec'] = array(
        'interval'  => 10,
        'display'   => __( 'Every 10 Second', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'every_30_minutes' ) ) {
    wp_schedule_event( time(), 'every_30', 'every_30_minutes' );
}

// Hook into that action that'll fire every 30 minutes
add_action( 'every_30_minutes', 'every_30_minutes_func' );
function every_30_minutes_func() {
    global $wpdb;
    $table = 'beds_properties';
    $rooms = $wpdb->get_results( "SELECT * FROM $table"); 
    foreach ($rooms as $room) {
        $product_id = 0;
        $room_id = intval($room->roomId);
        $regular_price = floatval($room->minPrice);
        $peoples = intval($room->maxPeople);

        $products = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                   'key'     => '_product_beds_id',
                   'value'   => $room_id,
                   'compare' => 'LIKE'
                )
             ),
        ));
        if(empty($products)){
            
            $post = array(
                'post_author' => 1,
                'post_content' => $room->propertyDescriptionBookingPage1en,  
                'post_status' => "publish",
                'post_title' => $room->nameRoom, 
                'post_type' => "product"
            );
            $post_id = wp_insert_post($post); 
            $product_id = $post_id;
            $product = wc_get_product( $post_id );
            update_post_meta( $post_id, '_visibility', 'visible' ); 
            update_post_meta( $post_id, '_downloadable', 'no'); 
            update_post_meta( $post_id, '_virtual', 'no'); 
            update_post_meta( $post_id, '_visibility', 'visible' ); 
            update_post_meta( $post_id, '_product_beds_id', $room_id );
            update_post_meta( $post_id, '_product_peoples',  $peoples);
            update_post_meta( $post_id, '_regular_price', $regular_price);  
            $product->set_regular_price( $peoples );
            
            wp_set_object_terms($post_id, "simple", 'product_type'); 

        }else{
            $_product_id = 0;
            foreach($products as $product){
                $_product_id = $product->ID;  
            }
            $product_id = $_product_id;
            $data = array(
                    'ID' => $_product_id,
                    'post_content' => $room->propertyDescriptionBookingPage1en,
                );
            $product = wc_get_product( $_product_id );
            update_post_meta( $post_id, '_visibility', 'visible' ); 
            update_post_meta( $post_id, '_downloadable', 'no'); 
            update_post_meta( $post_id, '_virtual', 'no'); 
            update_post_meta( $post_id, '_visibility', 'visible' ); 
            update_post_meta( $_product_id, '_product_beds_id', $room_id );
            update_post_meta( $_product_id, '_product_peoples',  $peoples);
            $product->set_regular_price( $peoples );

            wp_update_post( $data );
        }

        // images uploading code start
        $attachment_ids = [];
        if($room->images != NULL){
            $images = explode(",", $room->images);
            foreach($images as $image){
                $url = $image;
                $post_name = basename( $url );
                $attach_name = explode(".", $post_name);
                $attach_name = $attach_name[0];
                $attachment = wp_get_attachment_by_post_name( $attach_name );
                if ( $attachment ) {
                    // if attachment is exist
                    array_push($attachment_ids, $attachment->ID); 
                }else{
                    // download and create attachment
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';

                    $desc = "Beads Image";
                    $tmp = download_url( $url );

                    $file_array = [
                        'name'     => basename( $url ),
                        'tmp_name' => $tmp
                    ];

                    $attachment_id = media_handle_sideload( $file_array, 0 );
                    array_push($attachment_ids, $attachment_id);    
                    @unlink( $tmp );
                }
                 
            }         
        }
        if(!empty($attachment_ids)){ 
            set_post_thumbnail($product_id, $attachment_ids[0]);
            update_post_meta($product_id, '_product_image_gallery', implode(',',$attachment_ids));
        }
        // images uploading code end
    } 
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_beds_daily_event' ) ) {
    wp_schedule_event( time(), 'daily', 'my_beds_daily_event' );
}
/**
 * daily add +1 day in calendar in every prop
 */
add_action('my_beds_daily_event', 'my_beds_daily_event_function');
function my_beds_daily_event_function()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $date = date('Y-m-d', strtotime('+ 1 year'));
    $act->setDataInCalendar($date,$date);

}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_beds_hourly_event' ) ) {
    wp_schedule_event( time(), 'hourly', 'my_beds_hourly_event' );
}
/**
 * hourly update availability & prices by 3 month ahead
 */
add_action('my_beds_hourly_event', 'my_beds_hourly_event_function');
function my_beds_hourly_event_function()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime('+3 months'));
    $act->setDataInCalendar($startDate,$endDate);
    $act->updateCalendar();

}
if ( ! wp_next_scheduled( 'my_beds_10_sec_event' ) ) {
    wp_schedule_event( time(), 'ten_sec', 'my_beds_10_sec_event' );
}
add_action('my_beds_10_sec_event','my_beds_10_sec_event_func');
function my_beds_10_sec_event_func()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $act->clearAPIIter();
}


register_activation_hook( __FILE__, 'beds_plugin_activate' );
register_deactivation_hook( __FILE__, 'beds_plugin_deactivate' );

function beds_plugin_activate()
{
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();

    $act->createTokenTable();
    $act->createCalendarTable();
    $act->createMainTable();
    $act->createPriceRulesTable();

    $act->refreshToken();
    $act->setDataInCalendar();
    $act->setDataInPropTable();

}
function beds_plugin_deactivate()
{
    //
}

if( ! ( function_exists( 'wp_get_attachment_by_post_name' ) ) ) {
function wp_get_attachment_by_post_name( $post_name ) {
$args = array(
'posts_per_page' => 1,
'post_type' => 'attachment',
'name' => trim( $post_name ),
);
$get_attachment = new WP_Query( $args );
if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
return false;
}
return $get_attachment->posts[0];
}
}


function check_date_noavaible($room_id=NULL,$date_start=NULL,$date_end=NULL){
    global $wpdb;
    $table = 'beds_calendar';

    $results = $wpdb->get_results( "SELECT roomId FROM $table WHERE date BETWEEN '$date_start' AND '$date_end' AND avaliable = 0 AND roomId = '$room_id'");
    return $results;
}



//function beds_get_price($days = NULL, $post_id = NULL, $price = NULL){
//
//    global $wpdb;
//    $days = intval($days);
//    $table = $wpdb->prefix . 'postmeta';
//    $products = $wpdb->get_results( "SELECT meta_value FROM $table WHERE post_id = '$post_id' AND meta_key = '_product_beds_id'");
//    $room_id = $products[0]->meta_value;
//
//    $prices_rules = $wpdb->get_results( "SELECT minimumStay,maximumStay,pricePercent FROM beds_prices_rules WHERE roomId = '$room_id'");
//    $pricePercent = 0;
//    foreach($prices_rules as $rule){
//        $minimumStay = intval($rule->minimumStay);
//        $maximumStay = intval($rule->maximumStay);
//        if($days >= $minimumStay && $days <= $maximumStay){
//            $pricePercent = $rule->pricePercent;
//        }
//    }
//    $result = floatval($pricePercent)*$price;
//    return $result;
//}



function woocommerce_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["custom_price"] ) ) {
                $value['data']->set_price($value["custom_price"]);
            }
        }  
    }  
}
add_action( 'woocommerce_before_calculate_totals', 'woocommerce_custom_price_to_cart_item', 99 );


//woocommerce_payment_complete
add_action( 'woocommerce_order_status_changed', 'setParamsToBedsAndDB',10,1);
function setParamsToBedsAndDB( $order_id ) {


    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $re = $act->setBookingOnAPI($order_id);

//    die($re);
    update_post_meta($order_id, 'request_api_res', $re);

}

function beds24_menu_page()
{
    require_once(BEDS_DIR . '/views/admin-page.php');

}

function register_beds24_menu_page()
{
     add_menu_page('Beds24 Settings','Beds24 Settings', 'manage_options', 'beds24-settings', 'beds24_menu_page');
}
add_action( 'admin_menu', 'register_beds24_menu_page' );


function beds24_bad_order_admin_page()
{
    require_once(BEDS_DIR . '/views/admin-page-wc.php');

}
function register_beds24_bad_order_admin_page()
{
    global $wpdb;
    $res = $wpdb->get_results("select * from `wp_postmeta` WHERE `meta_key` = 'request_api_res'");
    $notif = 0;

    foreach ($res as $re) {
        if (empty($re->meta_value)){
            $notif++;
        }
        else{
            $resApiObj = json_decode($re->meta_value)[0];
            $apiSuccess = $resApiObj->success;
            if (!$apiSuccess){
                $notif++;
            }
        }
    }

    add_submenu_page('woocommerce','Beds24 failed order',$notif ? sprintf( 'Beds24 failed order <span class="awaiting-mod">%d</span>', $notif ) : 'Beds24 failed order', 'manage_options', 'beds24-bad-orders', 'beds24_bad_order_admin_page');
}
add_action( 'admin_menu', 'register_beds24_bad_order_admin_page' );


add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_data_as_order_item_meta_data', 20, 4 );
function save_cart_item_data_as_order_item_meta_data( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['booked_from'] ) ) {
        $item->update_meta_data( __( 'booked_from'), $values['booked_from'] );
    }
    if ( isset( $values['booked_to'] ) ) {
        $item->update_meta_data( __( 'booked_to'), $values['booked_to'] );
    }
    if ( isset( $values['persons'] ) ) {
        $item->update_meta_data( __( 'persons'), $values['persons'] );
    }
}

add_action('woocommerce_cart_calculate_fees' , 'add_custom_fees');

function add_custom_fees( WC_Cart $cart ){
    if(isset($_COOKIE['accompanied_dog'])){
        $fees = $_COOKIE['accompanied_dog'];
        $cart->add_fee( 'Accompanied by a dog', $fees);
    }
    if(isset($_COOKIE['cancellation'])){
        $fees = $_COOKIE['cancellation'];
        $cart->add_fee( 'Cancellation insurance', $fees);
    }
    if(isset($_COOKIE['final_cleaning'])){
        $fees = $_COOKIE['final_cleaning'];
        $fees = floatval($fees);
        $cart->add_fee( 'Final cleaning', $fees );
    }
}


//add_action( 'init', 'add_beds_product' );
//function add_beds_product() {
//    $order = wc_get_order( 156 );
//    foreach ($order->get_items() as $item_id => $item ) {
//        echo $item->get_meta('booked_from');
//    }
//}


add_action('woocommerce_checkout_before_terms_and_conditions', 'checkout_additional_checkboxes');
function checkout_additional_checkboxes( ){
    $checkbox_text = __( "Är du över 25 år? Vi har 25-års gräns på alla våra boenden. Bokningen kommer att makuleras om uppgiften är felaktig", "woocommerce" );
    ?>
    <p class="form-row custom-checkboxes">
        <label class="woocommerce-form__label checkbox custom-one">
            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="custom_one" > <span><?php echo  $checkbox_text; ?></span> <span class="required">*</span>
        </label>
    </p>
    <?php
}

add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
function my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( ! $_POST['custom_one'] )
        wc_add_notice( __( 'You must accept "Är du över 25 år?".' ), 'error' );

}