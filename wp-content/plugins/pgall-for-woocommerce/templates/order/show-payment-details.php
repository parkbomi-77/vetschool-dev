<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$payment_gateway = pafw_get_payment_gateway_from_order( $order );

if ( empty( $payment_gateway ) ) {
	return;
}

$tid                  = $payment_gateway->get_transaction_id( $order );
$receipt_url          = $payment_gateway->get_transaction_url( $order );
$receipt_popup_params = $payment_gateway->get_receipt_popup_params();

$paid_date = $order->get_date_paid();

if ( empty( $payment_gateway ) || empty( $tid ) || empty( $paid_date ) ) {
	return;
}

$order_cancel_url = pafw_get_order_cancel_url( $order, is_user_logged_in() ? $order->get_view_order_url() : '' );

?>

<div class="pafw-payment-details-section">
    <h2><?php echo __( '결제 상세 정보', 'pgall-for-woocommerce' ); ?></h2>

    <table class="pafw-payment-details woocommerce-table woocommerce-table--order-details shop_table order_details">
        <thead>
        <tr>
            <th class="woocommerce-table__ex-table ex-payment-method"><?php _e( '결제수단', 'pgall-for-woocommerce' ); ?></th>
            <th class="woocommerce-table__ex-table ex-payment-date"><?php _e( '결제일시', 'pgall-for-woocommerce' ); ?></th>
            <th class="woocommerce-table__ex-table ex-action"></th>
        </tr>
        </thead>
        <tr>
            <td><?php echo $payment_gateway->get_title(); ?></td>
            <td><?php echo $paid_date->date( 'Y-m-d H:i:s' ); ?></td>
            <td>
				<?php if ( ! empty( $receipt_url ) ) : ?>
					<?php if ( ! empty( $receipt_popup_params ) ) : ?>
                        <script>
                            jQuery( document ).ready( function ( $ ) {
                                $( 'a.pafw-view-receipt' ).on( 'click', function () {
                                    window.open( "<?php echo $receipt_url; ?>", "<?php echo $receipt_popup_params['name']; ?>", "<?php echo $receipt_popup_params['features']; ?>" );
                                    return false;
                                } );
                            } );
                        </script>
                        <a href="<?php echo $receipt_url; ?>" target="_blank" class="button pafw-view-receipt"><?php _e( '영수증 확인', 'pgall-for-woocommerce' ); ?></a>
					<?php else : ?>
                        <a href="<?php echo $receipt_url; ?>" target="_blank" class="button"><?php _e( '영수증 확인', 'pgall-for-woocommerce' ); ?></a>
					<?php endif; ?>
				<?php else: ?>
					<?php do_action( 'pafw_view_order_receipt_button_' . $payment_gateway->id, $order ); ?>
				<?php endif; ?>

	            <?php if( ! empty( $order_cancel_url ) ) : ?>
                    <a href="<?php echo $order_cancel_url; ?>" class="button button-primary cancel"><?php _e("주문취소","##PKGNAMEW##"); ?></a>
	            <?php endif; ?>
            </td>
        </tr>
    </table>
</div>