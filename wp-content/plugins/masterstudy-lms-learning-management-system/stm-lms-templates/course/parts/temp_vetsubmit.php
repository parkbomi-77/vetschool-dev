<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

$productid = $_POST['product_code'];
$product_cart_id = WC()->cart->generate_cart_id($productid);
$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

 
if( $in_cart === '' ){ // 장바구니에 담겨져있지않을 경우
    WC()->cart->add_to_cart( $productid, 1 );
    echo '<script>alert("상품을 장바구니에 담았습니다")</script>';

}else { // 장바구니에 이미 담겨져있는 경우
    echo '<script>alert("이미 장바구니에 담았습니다")</script>';
}

?>

