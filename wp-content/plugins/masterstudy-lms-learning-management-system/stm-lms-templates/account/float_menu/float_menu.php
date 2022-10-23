<?php
$lms_template_current = get_query_var( 'lms_template' );

$disallowed_templates = array(
	'stm-lms-lesson'
);

if ( in_array( $lms_template_current, $disallowed_templates ) ) {
	return false;
}

/*Check if float menu enabled*/
if ( ! STM_LMS_User_Menu::float_menu_enabled() ) {
	return false;
}

$position = STM_LMS_Options::get_option( 'float_menu_position', 'left' );

stm_lms_register_style( 'float_menu/menu' );
stm_lms_register_script( 'float_menu/menu' );

$classes   = array();
$classes[] = "__position_{$position}";
$classes[] = ( is_user_logged_in() ) ? '__logged_in' : '__logged_out';

do_action('stm_lms_user_float_menu_before');
?>

<div class="stm_lms_user_float_menu __collapsed <?php echo esc_attr( implode( ' ', $classes ) ) ?>">
	
	<div class="stm_lms_user_float_menu__toggle">
		
		<?php STM_LMS_Helpers::print_svg( 'assets/img/account/menu_toggle.svg' ); ?>
	
	</div>
	
	<?php if ( is_user_logged_in() ): ?>
		
		<?php STM_LMS_Templates::show_lms_template( 'account/float_menu/logged_in' ); ?>
	
	<?php else: ?>
		
		<?php STM_LMS_Templates::show_lms_template( 'account/float_menu/logged_out' ); ?>
	
	<?php endif; ?>

</div>

<div class="stm_lms_user_float_menu__tip heading_font"></div>