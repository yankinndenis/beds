<?php


?>

<div class="hotel_content">

    <h2><?php the_title(); ?></h2>

<!--    test-->
    <?php
//    var_dump($isAvaliable);
    echo "<br>";
    ?>
<!--    /test-->

    <div class="inner_hotel">

        <div class="gallery">

            <div class="slider">

                <?php

                
                if(!isset($_GET['date_start']) && !isset($_GET['date_end']) && !isset($_GET['adult'])){
                    array_push($notAvail, 'no');
                }
                if(!empty($gallery)){

                    foreach ( $gallery as $attachment_id ) {



                        $full_src = wp_get_attachment_image_src( $attachment_id, 'full' );



                        echo '<a data-fslightbox href="'.$full_src[0].'">';



                        echo '<div class="slide" style="background: url('.$full_src[0].')no-repeat 50% 50%; background-size: cover;"></div>';



                        echo '</a>';



                    }

                }else{

                    echo '<div class="slide" style="background: url('.$picture.')no-repeat 50% 50%; background-size: cover;"></div>';

                } ?>

            </div>

        </div>

        <div class="period">

            <p class="param" ><input readonly type="text" id="date-start"  value="<?php echo $date_stert; ?>"></p>

            <p class="param" ><input readonly type="text" id="date-end" value="<?php echo $date_end; ?>"></p>

            <p class="param">

                <select name="adult" id="adult">

                    <?php

                    for ($i=1; $i<=$maxAdult; $i++){

                        if ($i == $adult){

                            echo '<option selected>'.$i.'</option>';

                        } else {

                            echo '<option>'.$i.'</option>';

                        }

                    }

                    ?>

                </select>

            <p class="price">Pris: <?php echo $price_by_period; ?> SEK</p>

            <div class="buy_button">

                <a data-product_id="<?php echo $post_id; ?>" data-custom_price="<?php echo $price_by_period; ?>"  class="buy beds_add_to_cart <?php if (!empty($notAvail)){echo 'notBuy';}?>" href="">Boka</a>

                <div class="result">Added to cart</div>

            </div>

        </div>



    </div>

    <div class="content">

        <?php the_content();?>

    </div>

</div>