<?php
$results = get_posts(array(
    'post_type' => 'product',
    'product_tag' => $area, 
    'posts_per_page' => -1,
    'post__in'=> $product_ids,
    'meta_query' => $meta_arr,
    'orderby' => 'post__in',  
    'order' => 'ASC',
)); 
$prod = count($results);
$pages = ceil($prod/$per_pages);
if($current_page == $pages){
   $current_page_no_av = 1; 
}


$notAvail = [];
$date_start_w = date("w", strtotime($date_start));
$date_end_w = date("w", strtotime($date_end));
if($date_start_w == '1' || $date_start_w == '2' || $date_start_w == '3' || $date_start_w == '5' || $date_start_w == '6'){
    array_push($notAvail, $_GET['date_start']);
}
if($date_end_w == '1' || $date_end_w == '2' || $date_end_w == '3' || $date_end_w == '5' || $date_end_w == '6'){
    array_push($notAvail,$_GET['date_end']);
}


if(!empty($product_ids)){
    $loop = new WP_Query( array(
    'post_type' => 'product',
    'product_tag' => $area, 
    'posts_per_page' => $per_pages,
    'post__in'=> $product_ids,
    'meta_query' => $meta_arr,
    'orderby' => 'post__in', 
    'order' => 'DESC',
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $current_page
    )); 
}
if(!empty($product_ids_no_avaliable)){
    $loop_no_avaliable = new WP_Query( array(
    'post_type' => 'product',
    'product_tag' => $area, 
    'posts_per_page' => $per_pages,
    'meta_query' => $meta_arr,
    'post__in'=> $product_ids_no_avaliable,
    'orderby' => 'post__in', 
    'order' => 'DESC',
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $current_page_no_av
    )); 
}
?>



<?php
if(!empty($product_ids)){
    while ( $loop->have_posts() ): $loop->the_post(); 

        $permalink = get_the_permalink().'?'.$get_parrs;
        $post_id = get_the_id();
        $product = wc_get_product( $post_id );
        $regular_price = floatval($product->get_regular_price());

        // $breadcrumbs = get_post_meta($post_id,'_product_breadcrumbs', true);
        $current_tags = get_the_terms( $post_id, 'product_tag' );
        $tags_name_arr = [];
        foreach($current_tags as $tag){
            array_push($tags_name_arr,$tag->name);
        }
        $breadcrumbs = implode(" / ", $tags_name_arr);

        $hundtillåtet = get_post_meta($post_id,'_product_hundtillåtet', true);
        $wi_fi = get_post_meta($post_id,'_product_wi_fi', true);
        $bastu = get_post_meta($post_id,'_product_bastu', true);
        $oppen_spis = get_post_meta($post_id,'_product_oppen_spis', true);
        $skidförråd = get_post_meta($post_id,'_product_skidförråd', true);
        $diskmaskin = get_post_meta($post_id,'_product_diskmaskin', true);
        $twatt = get_post_meta($post_id,'_product_tvättmaskin', true);
        $tork = get_post_meta($post_id,'_product_torkskåp', true);
        $barnsang = get_post_meta($post_id,'_product_barnsäng', true);
        $barnstol = get_post_meta($post_id,'_product_barnstol', true);

        $product_details = $product->get_data();
        $product_short_description = $product_details['short_description'];
        $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $post_id);
        $peoples = intval(get_post_meta($post_id,'_product_peoples', true));
        $adult = intval($adult);
        

        if($adult<=$peoples){
            $picture = get_the_post_thumbnail_url($post_id,'middle');

            if($picture == false){
                $picture = 'https://testbeds.wp4u.link/wp-content/uploads/woocommerce-placeholder.png';
            }
            if(!empty($notAvail)){ ?>
                <div style="opacity: 0.7;" class="hotel">
            <?php }else{ ?>
                <div  class="hotel">
            <?php } ?>
                    <div onclick="window.open('<?php echo $permalink; ?>');" class="image" style="background:url('<?php echo $picture; ?>')no-repeat 50% 50%;background-size: cover;cursor: pointer;"></div>
                    <div class="content">
                        
                        <h2 style="cursor: pointer;" onclick="window.open('<?php echo $permalink; ?>');"><?php the_title(); ?></h2>
                        <?php 
                        // the_content(); 
                        ?>
                        <p class="breadcrumbs"><?php echo $breadcrumbs; ?></p>
                        <div class="icons">
                            <?php 
                            if($hundtillåtet){
                                echo '<img src='.BEDS_URL.'assets/img/dog.png">';
                            }
                            if($wi_fi){
                                echo '<img src='.BEDS_URL.'assets/img/wifi.png">';
                            }
                            if($skidförråd){
                                echo '<img src='.BEDS_URL.'assets/img/ski.png">';
                            }
                            if($oppen_spis){
                                echo '<img src='.BEDS_URL.'assets/img/bonfire.png">';
                            }
                            if($bastu){
                                echo '<img src='.BEDS_URL.'assets/img/sauna.png">';
                            }
                            if ($diskmaskin){
                                echo '<img src='.BEDS_URL.'assets/img/dishwasher.png">';
                            }
                            if ($twatt){
                                echo '<img src='.BEDS_URL.'assets/img/washing-machine-icon.png">';
                            }
                            if ($tork){
                                echo '<img src='.BEDS_URL.'assets/img/dry.png">';
                            }
                            if ($barnstol){
                                echo '<img src='.BEDS_URL.'assets/img/highchair.png">';
                            }
                            if ($barnsang){
                                echo '<img src='.BEDS_URL.'assets/img/cot.png">';
                            }

                            ?>
                        </div>
                        <p>
                            <?php echo $product_short_description; ?>
                        </p>  
                    </div>
                    <div class="price">
                        <div class="content_top">
                            <p class="period"><span>Period: <?php echo $period1.' - '. $period2?></span></p>
                            <p class="price_culc"><?php echo $price_by_period; ?> sek</p>
                        </div>
                        <div class="content_bottom">
                        <?php if(!empty($notAvail)){ ?>
                            
                            <a style="pointer-events: none;background: grey;" data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>" class="beds_add_to_cart" href="">inte tillgänglig</a>
                        <?php }else{ ?>
                            <a data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>" class="beds_add_to_cart" href="">BOKA</a>
                        <?php } ?>
                            
                            <div class="result">Added to cart</div>
                        </div>

                    </div>
                </div>
                
        <?php 
        }
    endwhile; wp_reset_postdata(); 
}

if(!empty($product_ids_no_avaliable) && $pages == $current_page){
    while ( $loop_no_avaliable->have_posts() ): $loop_no_avaliable->the_post(); 
        $permalink = get_the_permalink().'?'.$get_parrs;
        $post_id = get_the_id(); 
        $product = wc_get_product( $post_id );
        $regular_price = floatval($product->get_regular_price());

        // $breadcrumbs = get_post_meta($post_id,'_product_breadcrumbs', true);
        $current_tags = get_the_terms( $post_id, 'product_tag' );
        $tags_name_arr = [];
        foreach($current_tags as $tag){
            array_push($tags_name_arr,$tag->name);
        }
        $breadcrumbs = implode(" / ", $tags_name_arr);

        $hundtillåtet = get_post_meta($post_id,'_product_hundtillåtet', true);
        $wi_fi = get_post_meta($post_id,'_product_wi_fi', true);
        $bastu = get_post_meta($post_id,'_product_bastu', true);
        $oppen_spis = get_post_meta($post_id,'_product_oppen_spis', true);
        $skidförråd = get_post_meta($post_id,'_product_skidförråd', true);

        $product_details = $product->get_data();
        $product_short_description = $product_details['short_description'];

        $price_by_period = $act->getRoomPriceByDays($days,$date_start, $date_end, $post_id);
        $peoples = intval(get_post_meta($post_id,'_product_peoples', true));
        $adult = intval($adult);
        

        if($adult<=$peoples){
            $picture = get_the_post_thumbnail_url($post_id,'middle');

            if($picture == false){
                $picture = 'https://testbeds.wp4u.link/wp-content/uploads/woocommerce-placeholder.png';
            }
        ?>
            
                <div style="opacity: 0.7;" class="hotel">
                    <div onclick="window.open('<?php echo $permalink; ?>');" class="image" style="background:url('<?php echo $picture; ?>')no-repeat 50% 50%;background-size: cover;cursor: pointer;"></div>
                    <div class="content">
                        
                        <h2 style="cursor: pointer;" onclick="window.open('<?php echo $permalink; ?>');"><?php the_title(); ?></h2>
                        <?php 
                        // the_content(); 
                        ?>
                        <p class="breadcrumbs"><?php echo $breadcrumbs; ?></p>
                        <div class="icons">
                            <?php 
                            if($hundtillåtet){
                                echo '<img src='.BEDS_URL.'assets/img/dog.png">';
                            }
                            if($wi_fi){
                                echo '<img src='.BEDS_URL.'assets/img/wifi.png">';
                            }
                            if($skidförråd){
                                echo '<img src='.BEDS_URL.'assets/img/ski.png">';
                            }
                            if($oppen_spis){
                                echo '<img src='.BEDS_URL.'assets/img/bonfire.png">';
                            }
                            if($bastu){
                                echo '<img src='.BEDS_URL.'assets/img/sauna.png">';
                            }
                            if ($diskmaskin){
                                echo '<img src='.BEDS_URL.'assets/img/dishwasher.png">';
                            }
                            if ($twatt){
                                echo '<img src='.BEDS_URL.'assets/img/washing-machine-icon.png">';
                            }
                            if ($tork){
                                echo '<img src='.BEDS_URL.'assets/img/dry.png">';
                            }
                            if ($barnstol){
                                echo '<img src='.BEDS_URL.'assets/img/highchair.png">';
                            }
                            if ($barnsang){
                                echo '<img src='.BEDS_URL.'assets/img/cot.png">';
                            }
                            ?>
                        </div>
                        <p>
                            <?php echo $product_short_description; ?>
                        </p>  
                    </div>
                    <div class="price">
                        <div class="content_top">
                            <p class="period"><span>Period: <?php echo $period1.' - '. $period2?></span></p>
                            <p class="price_culc"><?php echo $price_by_period; ?> sek</p>
                        </div>
                        <div class="content_bottom">
                            <a style="pointer-events: none;background: grey;" data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>" class="beds_add_to_cart" href="">inte tillgänglig</a>
                            <div class="result">Added to cart</div>
                        </div>

                    </div>
                </div>
                
        <?php 
        }
    endwhile; wp_reset_postdata();
}
if($ajax == false && $per_pages < $prod){
$i=0;
?>
<div class="pagination_content">
    <div class="pagination">
        <?php
        $npage = NULL;
        $get_parrs = [];
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
        $url = $url[0];
        foreach($get as $key => $value){
            if($key != 'npage'){
               array_push($get_parrs,$key.'='.$value);  
            }  
        }
        $get_parrs = implode('&',$get_parrs);
        $current_page = intval($current_page);
        if($current_page > 1){
            $back = $current_page-1;
            echo '<a href="'.$url.'?'.$get_parrs.'&npage='.$back.'">«</a>';
        }
        while($i < $pages){
            $i++;
            if($current_page == $i){
                $class = 'class="active"';
            }else{
                $class = '';
            }
            echo '<a '.$class.' href="'.$url.'?'.$get_parrs.'&npage='.$i.'">'.$i.'</a>';
        }
        if($current_page < $pages){
            $next = $current_page+1;
            echo '<a href="'.$url.'?'.$get_parrs.'&npage='.$next.'">»</a>';
        }
        ?>
    </div>
</div>
<?php }