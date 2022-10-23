<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_Settlebank_Basic' ) ) {
	class PAFW_Settings_Settlebank_Basic extends PAFW_Settings_Settlebank {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => '기본 설정',
					'elements' => array (
						array (
							'id'       => 'pc_pay_method',
							'title'    => '결제수단',
							'default'  => 'settlebank_mybank',
							'type'     => 'Select',
							'multiple' => 'true',
							'options'  => WC_Gateway_PAFW_Settlebank::get_supported_payment_methods()
						),
					)
				),
				array (
					'type'     => 'Section',
					'title'    => '결제 설정',
					'elements' => array (
						array (
							'id'        => 'operation_mode',
							'title'     => '운영 모드',
							'className' => '',
							'type'      => 'Select',
							'default'   => 'sandbox',
							'options'   => array (
								'sandbox'    => '개발 환경 (Sandbox)',
								'production' => '운영 환경 (Production)'
							)
						),
						array (
							'id'          => 'test_user_id',
							'title'       => '테스트 사용자 아이디',
							'className'   => 'fluid',
							'showIf'      => array ( 'operation_mode' => 'sandbox' ),
							'placeHolder' => '테스트 사용자 아이디를 선택하세요.',
							'type'        => 'Text',
							'default'     => 'pgall_test_user',
							'desc2'       => __( '<div class="desc2">테스트 사용자 아이디가 지정된 경우, 관리자 및 테스트 사용자에게만 결제수단이 노출됩니다.</div>', 'pgall-for-woocommerce' ),
						),
						array (
							'id'        => 'merchant_id',
							'title'     => '가맹점 아이디',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '',
							'desc2'     => __( '<div class="desc2">세틀뱅크 가입 후 전달받으신 가맹점 아이디를 입력해주세요.<br>결제 테스트를 위한 가맹점 아이디 발급은 <code><a target="_blank" href="https://develop.sbsvc.online/">세틀뱅크 개발자 사이트</a></code>에서 신청하실 수 있습니다.</code></div>', 'pgall-for-woocommerce' ),
						),
						array (
							'id'        => 'merchant_key',
							'title'     => '가맹점 인증키',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '',
							'desc2'     => __( '<div class="desc2">세틀뱅크 가입 후 전달받으신 개인정보 암호키를 입력해주세요.<br>결제 테스트를 위한 가맹점 인증키 발급은 <code><a target="_blank" href="https://develop.sbsvc.online/">세틀뱅크 개발자 사이트</a></code>에서 신청하실 수 있습니다.</code></div>', 'pgall-for-woocommerce' ),
						),
					)
				),
				array (
					'type'     => 'Section',
					'title'    => '정기 결제 설정',
					'showIf'   => array ( 'pc_pay_method' => 'settlebank_mybank_subscription' ),
					'elements' => array (
						array (
							'id'         => 'operation_mode_subscription',
							'title'      => '운영 모드',
							'className'  => '',
							'type'       => 'Select',
							'default'    => 'sandbox',
							'allowEmpty' => false,
							'options'   => array (
								'sandbox'    => '개발 환경 (Sandbox)',
								'production' => '운영 환경 (Production)'
							)
						),
						array (
							'id'          => 'test_user_id_subscription',
							'title'       => '테스트 사용자 아이디',
							'className'   => 'fluid',
							'placeHolder' => '테스트 사용자 아이디를 선택하세요.',
							'showIf'      => array ( 'operation_mode_subscription' => 'sandbox' ),
							'type'        => 'Text',
							'default'     => 'pgall_test_user',
							'desc2'       => __( '<div class="desc2">개발 환경 (Sandbox) 모드에서는 관리자 및 테스트 사용자에게만 결제수단이 노출됩니다.</div>', 'pgall-for-woocommerce' ),
						),
						array (
							'id'        => 'subscription_merchant_id',
							'title'     => '가맹점 아이디',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '',
							'desc2'     => __( '<div class="desc2">세틀뱅크 가입 후 전달받으신 가맹점 아이디를 입력해주세요.<br>결제 테스트를 위한 가맹점 아이디 발급은 <code><a target="_blank" href="https://develop.sbsvc.online/">세틀뱅크 개발자 사이트</a></code>에서 신청하실 수 있습니다.</code></div>', 'pgall-for-woocommerce' ),
						),
						array (
							'id'        => 'subscription_merchant_key',
							'title'     => '가맹점 인증키',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '',
							'desc2'     => __( '<div class="desc2">세틀뱅크 가입 후 전달받으신 개인정보 암호키를 입력해주세요.<br>결제 테스트를 위한 가맹점 인증키 발급은 <code><a target="_blank" href="https://develop.sbsvc.online/">세틀뱅크 개발자 사이트</a></code>에서 신청하실 수 있습니다.</code></div>', 'pgall-for-woocommerce' ),
						),
						array (
							'id'        => 'management_batch_key',
							'title'     => '빌키 관리',
							'className' => '',
							'type'      => 'Select',
							'default'   => 'subscription',
							'options'   => array (
								'subscription' => '정기결제권',
								'user'         => '사용자'
							)
						),
						array (
							"id"        => "user_can_delete_batch_key",
							"title"     => __( "결제수단 삭제 허용", "pgall-for-woocommerce" ),
							'showIf'      => array ( 'management_batch_key' => 'user' ),
							"className" => "",
							"type"      => "Toggle",
							"default"   => "no",
							"desc"      => __( '<div class="desc2">고객은 "내계정 - 결제수단 관리 페이지"에서 자신이 등록한 결제수단을 삭제할 수 있습니다.</div>', 'pgall-for-woocommerce' )
						)
					)
				)
			);
		}
	}
}
