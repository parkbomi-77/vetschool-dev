<div id="box">
    <style>
        .boxbox{
            overflow-y: scroll;
            height: 605px;
        }
        .stm-lms-course__content{
            display:flex;
            justify-content: center;
            /* align-items: center; */
        }
        .sample111{
            width: 1200px;
        }

        #box {
            width: 28%;
            height: 660px;
            background-color: rgb(17, 17, 19);
            border: 2px solid rgb(17, 17, 19);
            margin-top: 134px;
            margin-left: -15px;
            margin-right: 13px;
        }
        @media (max-width: 1100px) {
            .stm-lms-course__content{
                flex-direction: column;
                align-items: center;
            }
            .sample111{
                width: 100%;
            }
            #box {
                width: 97%;
                margin: 0px;
            }
            .sample111>.container {
                height: 55px;
            }
        }
        #box>p i {
            font-size: 17px;
        }
        #box>p{
            padding:9px 15px 5px;
            font-size: 18px;
            background-color: black;
            margin-bottom: 0;
            color: #e1b475;
            font-weight: 600;
        }
        
        .boxbox>div {
            display: block;
            background-color: rgb(19, 21, 24);
            padding: 10px 5px 2px 17px;
            border-top: solid black;
            font-size: 15px;
            font-weight: 500;
            color: #d6d6d6;
            height: 90px;
        }
        .boxbox>div:hover {
            background-color: rgb(59, 62, 59);
        }
        .box-flex{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .box-name .box-title{
            line-height: 22px;
            font-size: initial;
        }
        .box-time{
            font-size: 13px;
            color: #8e8e8e;
            line-height: 14px;
            margin-top: 5px;
        }
        .box-cart{
            font-size: 20px;
            margin-right:4px;
        }

        .box-cart .quantity{
            display: none;
        }
        .box-cart i{
            color: #d6d6d6;
        }
        .box-cart button{
            background-color: hwb(14deg 23% 73% / 88%);
            border: 0;
            height: 67px;
            min-width: 50px;
            width: 70px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 600;
        }
        .box-cart button:last-child {
            background-color: rgb(235,183,48);
            font-weight: 600;
            color: white;
            padding: 0;
        }

        @media screen and (max-width: 1500px) {
            .box-flex{
                margin-top: 3px;
            }
            .box-cart{
                /* margin-right:4px; */

            }
            .box-cart button{
                width: 60px;
                height: 60px;
            }

        }
        .vetcart{
            display: inline;
        }
    </style>

    <p> <i class="fa-solid fa-store"></i> store</p>
    <div class="boxbox">
        <?php
            global $wpdb, $post;

            // lesson 글에 해당하는 재생시간등록 결과 && 현재 광고중인 제품만 노출되도록 하기
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_play_time inner join wp_product_list on wp_play_time.product_list_id = wp_product_list.ID where wp_product_list.adv_state = 1 and wp_play_time.posts_lesson_id = $post->ID"));
            $current_user = wp_get_current_user();

            if($results){
                $num = count($results);
                for($i=0; $i<$num; $i++){ 
                    $productname = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$results[$i]->product_list_id));
                    if($productname[0]->mall_code === "1"){ // 벳스쿨 제품일경우 벳스쿨 장바구니로 ?>
                        <?php      
                            $classname = "WC_Product_Simple";
                            $product = new $classname($productname[0]->product_code);
                        ?>
                        <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                            <form id="wishform" method='POST' target="iframe1"
                            action='/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_vetsubmit.php'>
                                <iframe id="iframe1" name="iframe1" style="display:none"></iframe>
                                <div class="box-flex">
                                    <div class="box-name">
                                        <div class="box-title">
                                            <?php echo $productname[0]->product_name ?> 
                                        </div>
                                        <div class="box-time"> 
                                            time
                                        </div>
                                    </div>
                                    <div class="box-cart">
                                        <!-- <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>">
                                        <input type="hidden" name="item_id" value="<?php echo $results[$i]->product_list_id ?>"> -->
                                        <input type="hidden" name="product_code" value="<?php echo $productname[0]->product_code ?>"> 

                                        <!-- 쇼핑몰로 바로가기 -->
                                        <button type="submit" formaction="/page-shop.php">바로가기</button> 
                                        
                                        <!-- 장바구니 담기 --> 
                                        <div class="vetcart">
                                            <button type='submit'>장바구니</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>


                    <?php
                    } else { // 타 사이트 제품일 경우 임시 장바구니로 ?>
                        <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                        <form id="wishform" method='POST' target="iframe1" 
                        action='/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_submit.php'>
                            <iframe id="iframe1" name="iframe1" style="display:none"></iframe>
                            <div class="box-flex">
                                <div>
                                    <?php echo $productname[0]->product_name ?> 
                                </div>
                                <div class="box-cart">
                                    <!-- 유저 아이디, 상품명 디비로 전달 -->
                                    <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $results[$i]->product_list_id ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $productname[0]->product_name ?>">
                                    <!-- 쇼핑몰로 바로가기 -->
                                    <button type="submit" formaction="/page-shop.php">바로가기</button> 
                                    <!-- 장바구니 담기 -->
                                    <button type='submit'>장바구니</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    <?php 
                    } ?>
                    
        <?php   }
            } ?>
    </div>
</div>

<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script> 
    const iframe = document.querySelector("iframe");
    const player = new Vimeo.Player(iframe);

    let TotalPlayingTime = 0; //전체 재생시간 
    player.getDuration().then((duration) => {
        TotalPlayingTime = duration;
    })

    const getCurrentTime = () => {
        player.getCurrentTime().then((currentTime) => { // 현재 재생시간

        currentTime = Math.round(currentTime) // 반올림하여 정수로 통일

            <?php 
            $num = count($results);
            for($i=0; $i<$num; $i++){ ?>
                document.getElementById(<?= $results[$i]->play_idx ?>).style.display = "none"
            <?php 
                }
                
            for($i=0; $i<$num; $i++){ 
            $time = $results[$i]->product_time;
           // var_dump($results[$i]->product_time);
                $minute = substr($time, 0, 2); 
                $seconds = substr($time, 3, 2); 
                $play_time = $minute*60 + $seconds; ?> //초로 변환하기 
                if(<?= $play_time ?> <= currentTime && currentTime <= <?= $play_time+4 ?>) { // 시작시간, 끝시간 설정 
                    let block = document.getElementById(<?= $results[$i]->play_idx ?>);
                    block.style.display = "block";
                    block.querySelector('.box-time').innerText = "<?php echo $time ?>";
                }
            <?php
            } ?>
           

        });   
    }
   let interval = setInterval(getCurrentTime, 500);

   function vetcartmodal() {
        alert('장바구니에 담았습니다');
   }
   function vetcartmodal2(){
        alert('이미 장바구니에 담았습니다');
   }



</script>
