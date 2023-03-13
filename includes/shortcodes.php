<?php
/**
 * add shortcode for Litepicker
 */

add_shortcode('litepicker','litepicker');
function litepicker()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script");
    wp_enqueue_script("beds-register-script");

    require_once(BEDS_DIR . '/views/start-form.html');

    return ob_get_clean();

}

add_shortcode('litepicker_list','litepicker_list');
function litepicker_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script");
    wp_enqueue_script("beds-register-script");

    $args = array( 'hide_empty' => 0 );
    $terms = get_terms('product_tag', $args );

    require_once(BEDS_DIR . '/views/res-form.php');

    return ob_get_clean();

}

add_shortcode('litepicker_res_list','litepicker_res_list');
function litepicker_res_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-register-style");

    wp_enqueue_script("beds-buttons-script");
    wp_enqueue_script("beds-moment-script");
    wp_enqueue_script("beds-litepicker-script");
    // wp_enqueue_script("beds-product-script");
    wp_enqueue_script("beds-register-script");

    

    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();

    global $wpdb;
    $table = 'beds_calendar';
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];

    $results = $wpdb->get_results( "SELECT roomId FROM $table WHERE (date BETWEEN '$date_start' AND '$date_end') GROUP BY roomId"); 

    $meta_query_array_avaliable = [];
    $meta_query_array_noavaliable = [];  
    $i=0;
    foreach ($results as $result) {
        $room_id = intval($result->roomId);
        $check = check_date_noavaible($room_id,$date_start,$date_end);
        if(!empty($check)){
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");

            array_push($meta_query_array_noavaliable, $products);
        }else{
            $table = $wpdb->prefix . 'postmeta';
            $products = $wpdb->get_results( "SELECT post_id FROM $table WHERE meta_value = '$room_id'");
            array_push($meta_query_array_avaliable, $products);
        }
        $i++;
     } 
    $product_ids = [];
    $product_ids_no_avaliable = []; 

    foreach($meta_query_array_avaliable as $product){
        $product_id = intval($product[0]->post_id);
        array_push($product_ids,$product_id);
    }
    foreach($meta_query_array_noavaliable as $product){
        $product_id = intval($product[0]->post_id);
        array_push($product_ids_no_avaliable,$product_id);
    }
    if(!empty($meta_query_array_avaliable)){
        // Creates DateTime objects
        $date_period1 = new DateTime($_GET['date_start']);
        $date_period2 = new DateTime($_GET['date_end']);
        $period1 = $date_period1->format('d.m');
        $period2 = $date_period2->format('d.m');
        $datetime1 = date_create($_GET['date_start']);
        $datetime2 = date_create($_GET['date_end']);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->days;
        $adult = $_GET['adult'];
        $date_end = $_GET['date_end'];
        $date_start = $_GET['date_start'];
        update_option('product_ids',$product_ids);
        update_option('product_ids_no_avaliable',$product_ids_no_avaliable);
        update_option('date_start',$date_start);
        update_option('date_end',$date_end);        
        update_option('adult',$adult);
        if(isset($_GET['area'])){
            update_option('area',$_GET['area']);
            $area = $_GET['area'];
        }else{
            update_option('area','');
            $area = '';
        }
        ?>
        <div class="hotels">
            <?php
                $per_pages = 10;
                if(isset($_GET['npage'])){
                    $current_page = $_GET['npage'];
                }else{
                    $current_page = 1;
                }
                
                $get = $_GET;
                $get_parrs = [];
                foreach($get as $key => $value){
                    array_push($get_parrs,$key.'='.$value);
                }
                $get_parrs = implode('&',$get_parrs);
                $ajax = false;
                
                require_once BEDS_DIR . '/tamplates/products.php';
            ?>
        </div>
        <?php
    }
    
    return ob_get_clean();
}

add_shortcode('page_hotel_inner','litepicker_hotel_inner');
function litepicker_hotel_inner()
{
    ob_start();
    wp_enqueue_style("beds-slick-style");
    wp_enqueue_style("beds-slick_theme-style");
    wp_enqueue_style("beds-register-style");
    wp_enqueue_style("beds-litepicker-style");
    wp_enqueue_style("beds-hotel-style");

    wp_enqueue_script("beds-litepicker-script");
    wp_enqueue_script("beds-slick-script");
    wp_enqueue_script("beds-fslightbox-script");
    wp_enqueue_script("beds-product-script");

    global $product;
    $post_id = get_the_id();
    $product = wc_get_product();
    $gallery        = $product->get_gallery_image_ids();
    $picture = 'https://testbeds.wp4u.link/wp-content/uploads/woocommerce-placeholder.png';
    $date_stert = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $adult = $_GET['adult'];
    $datetime1 = date_create($_GET['date_start']);
    $datetime2 = date_create($_GET['date_end']);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days;
    require_once(BEDS_DIR . '/includes/class.action.php');
    $act = new \beds_booking\Action_beds_booking();
    $price_by_period = $act->getRoomPriceByDays($days,$_GET['date_start'], $_GET['date_end'], $post_id);

    $room = get_post_meta($post_id, '_product_beds_id', true);
    $isAvaliable = $act->getIsAvailable($room,$_GET['date_start'],$_GET['date_end']);
    $noAvailInDB = check_date_noavaible($room,$_GET['date_start'],$_GET['date_end']);
    $maxAdult = $act->getNumAdult($room);
    $notAvail = array(); // array with dates booked and close
    if ($isAvaliable ["success"]){
        foreach ($isAvaliable['data'][0]["availability"] as $key => $val) {
            if (!$val){
                array_push($notAvail,$key);
            }
        }
    }
    if(empty($noAvailInDB)){
        $act->updateAvailByRoom($room,$notAvail);
    }

    $excl = getAvailByRoomIDexcl($post_id);
    foreach ($notAvail as $key => $value) {
        if (in_array($value, $excl)) {
            unset($notAvail[$key]);
        }
    }

    $date_start_w = date("w", strtotime($_GET['date_start']));
    $date_end_w = date("w", strtotime($_GET['date_end']));
    if($date_start_w == '1' || $date_start_w == '2' || $date_start_w == '3' || $date_start_w == '5' || $date_start_w == '6'){
        array_push($notAvail, $_GET['date_start']);
    }
    if($date_end_w == '1' || $date_end_w == '2' || $date_end_w == '3' || $date_end_w == '5' || $date_end_w == '6'){
        array_push($notAvail,$_GET['date_end']);
    }

    require_once BEDS_DIR.'/views/single-prod.php';

    return ob_get_clean();
}


add_shortcode('bottom_modal_beds','bottom_modal_beds');
function bottom_modal_beds(){
    ob_start();
    ?>
    <style>
        .beds-modal{
            color: black;
            background-color: white;
            width: 50%;
            height: auto;
            padding: 20px;
            border: 3px solid #e83939;
            border-radius: 20px;
            text-align: center;
            position: fixed;
            z-index: 999999;
            left: 25%;
            top: 40%;
        }
        .beds-modal-btn button{
            color: white;
            background: #e83939;
            font-size: 20px;
            font-weight: 500;
            border-radius: 20px;
        }
        .owf-modal{
            background: rgba(140, 140, 140, 0.59);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            z-index: 99999;
            right: 0;
            display: none;
        }
    </style>
    <div class="owf-modal" id="beds24-modal">
        <div class="beds-modal">
            <div class="beds-modal-header">
                <h3 id="modal-head-beds"></h3>
            </div>
            <div class="beds-modal-body">
                <p id="modal-text-beds"></p>
            </div>
            <div class="beds-modal-btn">
                <button>OK</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('api','api');
function api()
{
    ob_start();
//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    global $wpdb;
//
//    $res = $wpdb->get_results("select * from `wp_postmeta` WHERE `meta_key` = 'request_api_res'");
//    $notif = 0;
//
//    foreach ($res as $re) {
//        if (empty($re->meta_value)){
//            $notif++;
//        }
//        else{
//            $resApiObj = json_decode($re->meta_value)[0];
//            $apiSuccess = $resApiObj->success;
//          if (!$apiSuccess){
//              $notif++;
//          }
//        }
//    }
//    var_dump($notif);
    ?>



    <?php


//    echo '<pre>';
//    $orderID = 198;
//    $order = wc_get_order($orderID);
//
////    var_dump();
//
////    foreach ( $order->get_items() as $item_id => $item ) {
////        $product_id = $item->get_product_id();
////        $variation_id = $item->get_variation_id();
////        $product = $item->get_product(); // see link above to get $product info
////        $product_name = $item->get_name();
////
////        $quantity = $item->get_quantity();
////        $subtotal = $item->get_subtotal();
////        $total = $item->get_total();
////        $tax = $item->get_subtotal_tax();
////        $tax_class = $item->get_tax_class();
////        $tax_status = $item->get_tax_status();
////        $allmeta = $item->get_meta_data();
////        $somemeta = $item->get_meta( '_whatever', true );
////        $item_type = $item->get_type(); // e.g. "line_item", "fee"
//////        var_dump($item_type);
////    }
//
//    foreach ($order->get_items() as $item_id => $item) {
//
//        $from = $item->get_meta('booked_from');
//
//        $to = $item->get_meta('booked_to');
//
//        $persons = $item->get_meta('persons');
//
//        $prodID = $item->get_data()['product_id'];
//
//        $roomId = get_post_meta($prodID, '_product_beds_id', true);
//
//        $data = $order->get_data();
//
//        $billing_first_name = $data['billing']['first_name'];
//
//        $billing_last_name = $data['billing']['last_name'];
//
//        $billing_address_1 = $data['billing']['address_1'];
//
//        $billing_city = $data['billing']['city'];
//
//        $billing_state = $data['billing']['state'];
//
//        $billing_postcode = $data['billing']['postcode'];
//
//        $billing_country = $data['billing']['country'];
//
//        $mail = $data['billing']['email'];
//
//        $tel = $data['billing']['phone'];
//
//        $invoiceArr = [];
//
//        foreach( $order->get_items('fee') as $item_fee_id => $item_fee ){
//
//            $fee_name = $item_fee->get_name();
//            $fee_total = $item_fee->get_total();
//            $quantity = $item_fee->get_quantity();
//
//            array_push($invoiceArr,  [
//                "type" => "charge",
//                "qty"=>$quantity,
//                "amount"=>$fee_total,
//                "lineTotal"=>$fee_total,
//                "description"=>$fee_name
//            ]);
//
//        }
//
//
//
//        $post = [
//
//            [
//
//                'roomId' => $roomId,
//
//                "status" => "confirmed",
//
//                "arrival" => $from,
//
//                "departure" => $to,
//
//                "numAdult" => $persons,
//
//                "numChild" => 0,
//
//                "firstName" => $billing_first_name,
//
//                "lastName" => $billing_last_name,
//
//                "email" => $mail,
//
//                "mobile" => $tel,
//
//                "address" => $billing_address_1,
//
//                "city" => $billing_city,
//
//                "state" => $billing_state,
//
//                "postcode" => $billing_postcode,
//
//                "country" => $billing_country,
//
//                "invoiceItems" => $invoiceArr
//
//            ]
//
//        ];
//    }
////
//        var_dump($post);
//    setParamsToBedsAndDB(186);
//    $act->setBookingOnAPI(184 );
//
//    $room = get_post_meta(82,'_product_beds_id', true);
//    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=0";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    $timeArr = array();
//    foreach ($res as $re) {
//        array_push($timeArr,$re['date']);
//    }
//    echo json_encode($timeArr,320);
//    $room = get_post_meta(82,'_product_beds_id', true);
//    var_dump($room);
//    $sql = "select `date` from `beds_calendar` where roomId='$room' and `isBooked`=1 and `avaliable`=1";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    $timeArr = array();
//    foreach ($res as $re) {
//        array_push($timeArr,$re['date']);
//    }
//    var_dump($timeArr);
//    var_dump($room);
//    $sql = "select `avaliable`,`date` from `beds_calendar` where roomId='$room'";
//    $res = $wpdb->get_results($sql,ARRAY_A);
//    var_dump($res);
//    echo json_encode($res,320);
//    foreach ($res as $item) {
//        var_dump($item);
//
//    }
//    $act->setBookingOnAPI(156);
//    $order = wc_get_order( 156 );
//    var_dump($order);
//    var_dump(get_post_meta(156));
//    foreach ($order->get_items() as $item_id => $item ) {
//        $from = $item->get_meta('booked_from');
//        $to = $item->get_meta('booked_to');
//        $persons = $item->get_meta('persons');
//        $prodID = $item->get_data()['product_id'];
//        $roomId = get_post_meta($prodID, '_product_beds_id', true);
//        $data = $order->get_data();
//        $billing_first_name = $data['billing']['first_name'];
//        $billing_last_name  = $data['billing']['last_name'];
//        $billing_company    = $data['billing']['company'];
//        $billing_address_1  = $data['billing']['address_1'];
//        $billing_address_2  = $data['billing']['address_2'];
//        $billing_city       = $data['billing']['city'];
//        $billing_state      = $data['billing']['state'];
//        $billing_postcode   = $data['billing']['postcode'];
//        $billing_country    = $data['billing']['country'];
//        $mail = $data['billing']['email'];
//        $tel = $data['billing']['phone'];
//        var_dump($tel.$mail);
//    }

//    require_once(BEDS_DIR . '/includes/class.action.php');
//    $act = new \beds_booking\Action_beds_booking();
//    $date = date('Y-m-d', strtotime('+ 1 year'));
//    var_dump($date);
//        $isAvaliable = $act->getIsAvailable('371906','2023-01-12','2023-01-19');
//    $act->checkAPICalls();
//var_dump($isAvaliable);
//var_dump($re);
//$photos = array();
//    foreach ($re->hosted as $item) {
//        if(!empty($item->url)){
//            array_push($photos,$item->url);
//        }
//    }
//var_dump(implode(',',$photos));

//    $date = date('Y-m-d', strtotime('+ 1 year'));
//    $act->setDataInCalendar($date,$date);

//    $res = $wpdb->get_var('SELECT count(`id`) from `beds_calendar`');
//    var_dump((int)$res != 0);
//    var_dump($act->setDataInTable());
//    var_dump($act->getPropDataByID(array(170082)));
    return ob_get_clean();
}

add_shortcode('filters_list','filters_list');
function filters_list()
{
    ob_start();
    wp_enqueue_style("beds-bootstrap-style");
    wp_enqueue_style("beds-register-style");
    wp_enqueue_script('beds-filters-script');

    require_once BEDS_DIR.'/views/filters.php';

    return ob_get_clean();
}
