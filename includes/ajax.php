<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function addtocart(){

    global $woocommerce;
    $product_id = intval($_POST['product_id']);
    $custom_price = floatval($_POST['custom_price']);
    $cart_item_data = array('custom_price' => $custom_price,'booked_from'=>$_POST['date_from'],'booked_to'=>$_POST['date_to'],'persons'=>$_POST['persons']);
    WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation, $cart_item_data);
    WC()->cart->calculate_totals();
    WC()->cart->set_session();
    WC()->cart->maybe_set_cart_cookies();

    $result['shipping'] = 123;
    
    echo json_encode($result, 320);

    wp_die();

} //endfunction

add_action('wp_ajax_addtocart', 'addtocart');
add_action('wp_ajax_nopriv_addtocart', 'addtocart');


function getAvailByRoomID(){
    global $wpdb;
    $room = get_post_meta($_POST['product_id'],'_product_beds_id', true);
    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=1 and `avaliable`=1";
    $res = $wpdb->get_results($sql,ARRAY_A);
    $timeArr = array();
    foreach ($res as $re) {
        array_push($timeArr,$re['date']);
    }


    $sql_asc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` asc";
    $sql_desc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` desc";
    $res_asc = $wpdb->get_results($sql_asc,ARRAY_A);
    $res_desc = $wpdb->get_results($sql_desc,ARRAY_A);

    $exclude_arr = [];
    $day_plus = 0;
    foreach ($res_asc as $re) {
        $day = date('Y-m-d', strtotime($re['date']));
        if($day != $day_plus){
            $day_w = date('w', strtotime($re['date']));
            if($day_w == '4' || $day_w == '0'){
                array_push($timeArr,$day);
            } 
        }
        $day_plus = date('Y-m-d', strtotime($re['date'] . ' +1 day'));
    }

    $day_minus = 0;
    foreach ($res_desc as $re) {
        $day = date('Y-m-d', strtotime($re['date']));
        if($day != $day_minus){
            $day_w = date('w', strtotime($re['date']));
            if($day_w == '4' || $day_w == '0'){
                array_push($timeArr,$day);
            }  
        }
        $day_minus = date('Y-m-d', strtotime($re['date'] . ' -1 day'));
    }
    echo json_encode($timeArr,320);

}
add_action('wp_ajax_getAvailByRoomID','getAvailByRoomID');
add_action('wp_ajax_nopriv_getAvailByRoomID','getAvailByRoomID');

function getAvailByRoomIDexcl($post_id){
    global $wpdb;
    $room = get_post_meta($post_id,'_product_beds_id', true);
    $sql_asc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` asc";
    $sql_desc = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0 order by `date` desc";
    $res_asc = $wpdb->get_results($sql_asc,ARRAY_A);
    $res_desc = $wpdb->get_results($sql_desc,ARRAY_A);

    $exclude_arr = [];
    $day_plus = 0;
    foreach ($res_asc as $re) {
        $day = date('Y-m-d', strtotime($re['date']));
        if($day != $day_plus){
            $day_w = date('w', strtotime($re['date']));
            if($day_w == '4' || $day_w == '0'){
                array_push($exclude_arr,$day);
            } 
        }
        $day_plus = date('Y-m-d', strtotime($re['date'] . ' +1 day'));
    }

    $day_minus = 0;
    foreach ($res_desc as $re) {
        $day = date('Y-m-d', strtotime($re['date']));
        if($day != $day_minus){
            $day_w = date('w', strtotime($re['date']));
            if($day_w == '4' || $day_w == '0'){
                array_push($exclude_arr,$day);
            }  
        }
        $day_minus = date('Y-m-d', strtotime($re['date'] . ' -1 day'));
    }
    return $exclude_arr;
}



function getPeriodByRoomID(){
    global $wpdb;
    $room = get_post_meta($_POST['product_id'],'_product_beds_id', true);
    $sql = "select `date` from `beds_calendar` where roomId='$room' and `avaliable`=0";
    $res = $wpdb->get_results($sql,ARRAY_A);
    $timeArr = array();
    foreach ($res as $re) {
        array_push($timeArr,$re['date']);
    }

    $excl = getAvailByRoomIDexcl($_POST['product_id']);

    foreach ($timeArr as $key => $value) {
        if (in_array($value, $excl)) {
            unset($timeArr[$key]);
        }
    }

    echo json_encode($timeArr,320);

}
add_action('wp_ajax_getPeriodByRoomID','getPeriodByRoomID');
add_action('wp_ajax_nopriv_getPeriodByRoomID','getPeriodByRoomID');


function filter_products(){
    $params1 = explode('/', $_POST['param1']);
    $params2 = explode('/', $_POST['param2']);
    $sovrum = trim($_POST['sovrum']);
    $skidlift = trim($_POST['skidlift']);
    $sovrum = intval($sovrum);
    $skidlift = intval($skidlift);
    $date_start = get_option('date_start');
    $date_end = get_option('date_end');
    $date_period1 = new DateTime($date_start);
    $date_period2 = new DateTime($date_end);
    $period1 = $date_period1->format('d.m');
    $period2 = $date_period2->format('d.m');
    $datetime1 = date_create($date_start);
    $datetime2 = date_create($date_end);
    $interval = date_diff($datetime1, $datetime2);
    $days = $interval->d;
    $adult = get_option('adult');
    $area = get_option('area');
    $product_ids = get_option('product_ids');
    $product_ids_no_avaliable = get_option('product_ids_no_avaliable');
    
    
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $meta_arr = [];
    $ajax = true;
    // global $wpdb;
    // $table = $wpdb->prefix . 'postmeta';
    // $params = array_merge($params1,$params2);
    // $product_ids_filtred = [];
    // var_dump($params);
    // foreach($params as $param){
    //     foreach($product_ids as $product_id){
    //         $results = $wpdb->get_results( "SELECT post_id FROM $table WHERE post_id = '$product_id' AND meta_key in ('_product_bastu','_product_diskmaskin','_product_hundtillåtet','_product_tvättmaskin')");
    //         if($results[0]->post_id != NULL){
    //             array_push($product_ids_filtred, $results[0]->post_id);
    //         }
    //     }
    // }
    // var_dump($product_ids_filtred);

    foreach ($params1 as $param1) {
        if($param1){
            $value = array('key' => $param1,'value' => 'yes','compare' => '=');
            array_push($meta_arr, $value);
        }
    }
    foreach ($params2 as $param2) {
        if($param2){
           $value = array('key' => $param2,'value' => 'yes','compare' => '=');
            array_push($meta_arr, $value); 
        } 
    }
    if($sovrum){
        $sovrum_value = array('key' => '_product_sovrum','value' => $sovrum,'type' => 'numeric','compare' => '<=');
        array_push($meta_arr, $sovrum_value); 
    } 
    if($skidlift){
        $skidlift_value = array('key' => '_product_skidlift','value' => $skidlift,'type' => 'numeric','compare' => '<=');
        array_push($meta_arr, $skidlift_value); 
    } 
    
    require_once BEDS_DIR . '/tamplates/products.php';

    // $result['sovrum'] = trim($_POST['sovrum']);
    // $result['skidlift'] = trim($_POST['skidlift']);
    // $result['param1'] = explode('/', $_POST['param1']);
    // $result['param2'] = explode('/', $_POST['param2']);

    // echo json_encode($result, 320);

    wp_die();

} //endfunction

add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');



function resolvedManualOrder()
{
    if (isset($_POST['order_id'])){

        $val = '['.json_encode(array('success'=>true)).']';

        update_post_meta($_POST['order_id'],'request_api_res',$val);
    }
}
add_action('wp_ajax_resolvedManualOrder', 'resolvedManualOrder');
add_action('wp_ajax_nopriv_resolvedManualOrder', 'resolvedManualOrder');