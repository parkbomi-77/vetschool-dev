<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'MSM_Shortcodes' ) ) :

	class MSM_Shortcodes {
		public static function init() {
			$shortcodes = array(
				'mshop_terms_for_customer'   => array( __CLASS__, 'mshop_terms_for_customer' ),
				'mshop_form_designer'        => array( __CLASS__, 'mshop_form_designer' ),
				'mshop_form_designer_simple' => array( __CLASS__, 'mshop_form_designer_simple' ),
				'mshop_form_step_container'  => array( __CLASS__, 'mshop_form_step_container' ),
				'mshop_form_step_item'       => array( __CLASS__, 'mshop_form_step_item' ),
				'mshop_form_step_navigator'  => array( __CLASS__, 'mshop_form_step_navigator' ),
				'msm_email_authentication'   => array( __CLASS__, 'email_authentication' )
			);

			foreach ( $shortcodes as $shortcode => $function ) {
				add_shortcode( $shortcode, $function );
			}
		}
		public static function mshop_form_step_container( $attrs, $content = null ) {
			$params = shortcode_atts( array(
				'number'     => 'three',
				'custom_css' => ''
			), $attrs );

			$result = '<div class="ui ' . $params['number'] . ' ordered top attached steps ' . $params['custom_css'] . ' mfs_wrapper">';
			$result .= do_shortcode( $content );
			$result .= '</div>';

			return $result;
		}
		public static function mshop_form_step_item( $attrs, $content = null ) {
			$params = shortcode_atts( array(
				'title'       => '',
				'description' => ''
			), $attrs );

			$result = '<div class="step mfs_item">';
			$result .= '<div class="content">';
			$result .= '<div class="title">' . $params['title'] . '</div>';
			$result .= '<div class="description">' . $params['description'] . '</div>';
			$result .= '</div>';
			$result .= '</div>';

			return $result;
		}
		public static function mshop_form_step_navigator( $attrs, $content = null ) {
			$params = shortcode_atts( array(
				'prev_title' => '이전',
				'next_title' => '다음'
			), $attrs );

			?>
            <div class="ui segment mfs_navigator">
                <div class="ui blue prev button"><?php _e( $params['prev_title'] ); ?></div>
                <div style="float: right" class="ui blue next button"><?php _e( $params['next_title'] ); ?></div>
            </div>
			<?php
		}
		public static function mshop_form_designer_step_container( $container ) {
			$shortcodes = "[mshop_form_step_container number='" . $container['property']['count'] . "' custom_css='" . msm_get( $container['property'], 'class' ) . "']";

			foreach ( $container['property']['items'] as $step ) {
				if ( 'StepItem' == $step['type'] ) {
					$shortcodes .= sprintf( "[mshop_form_step_item title='%s' description='%s']",
						$step['property']['title'], $step['property']['desc'] );
				}
			}
			$shortcodes .= "[/mshop_form_step_container]";

			$i = 0;
			foreach ( $container['property']['items'] as $step ) {
				if ( 'StepItem' == $step['type'] ) {
					$shortcodes .= sprintf( "[mshop_form_designer slug='%s' %s]", array_keys( $step['property']['form_id'] )[0], 0 === $i ++ ? 'default=true' : '' );
				}
			}

			return do_shortcode( $shortcodes );
		}
		public static function get_field_rules( $formdata ) {
			$field_rules = array();
			if ( ! empty( $formdata ) ) {
				foreach ( $formdata as $element ) {
					$property = $element['property'];

					if ( 'FormField' === $element['type'] ) {
						$field_rules = array_merge( $field_rules, self::get_field_rules( $element['property']['items'] ) );
					} else if ( 'Agreement' === $element['type'] ) {
						if ( is_array( $element['property']['agreement_type'] ) ) {
							$type = array_keys( $element['property']['agreement_type'] )[0];
						} else {
							$type = $element['property']['agreement_type'];
						}

						$agreements = MSM_Manager::get_terms_and_conditions( $type );

						foreach ( $agreements as $agreement ) {
							$agreement = new MSM_Agreement( $agreement );
							if ( 'yes' == $agreement->mandatory ) {
								$field_rules[ $agreement->slug ] = array(
									'rules' => array(
										array(
											'type'   => 'checked',
											'prompt' => $agreement->title . '에 동의하셔야 합니다.'
										)
									)
								);
							}
						}
					} else if ( 'Input' === $element['type'] && 'password' == $property['type'] ) {
						if ( ! empty( $property['use_strength_meter'] ) && 'yes' == $property['use_strength_meter'] ) {
							$field_rules[ $property['name'] ] = array(
								'rules' => array(
									array(
										'type'   => ( 'yes' == msm_get( $property, 'required', 'no' ) ) ? 'validatePasswordStrength' : 'validatePasswordStrengthAllowEmpty',
										'prompt' => __( '더 안전한 비밀번호를 입력해주세요.', 'mshop-members-s2' )
									)
								)
							);
						} else {
							$rules = array();

							if ( 'password' == $property['name'] && ! empty( $property['required'] ) && 'yes' === $property['required'] ) {
								$rules[] = array(
									'type'   => 'empty',
									'prompt' => __( '비밀번호를 입력하세요.', 'mshop-members-s2' )
								);
							}

							if ( 'confirm_password' == $property['name'] ) {
								$rules[] = array(
									'type'   => 'match[password]',
									'prompt' => __( '비밀번호가 일치하지 않습니다.', 'mshop-members-s2' )
								);
							}

							if ( ! empty( trim( msm_get( $property, 'regExp' ) ) ) ) {
								$rules[] = array(
									'type'   => 'regExp[' . msm_get( $property, 'regExp' ) . ']',
									'prompt' => msm_get( $property, 'regExpMsg' )
								);
							}

							$field_rules[ $property['name'] ] = array(
								'rules' => $rules
							);
						}
					} else if ( 'Recaptcha' === $element['type'] ) {
						$field_rules['_grecaptcha'] = array(
							'rules' => array(
								array(
									'type'   => 'empty',
									'prompt' => __( 'reCAPTCHA를 클릭해주세요.', 'mshop-members-s2' )
								)
							)
						);
					} else if ( 'Authentication' === $element['type'] ) {
						$field_rules[ $property['id'] ] = array(
							'rules' => array(
								array(
									'type'   => 'empty',
									'prompt' => __( '간편인증을 진행해주세요.', 'mshop-members-s2' )
								)
							)
						);
					} else if ( 'Phone' === $element['type'] ) {
						if ( ! empty( $property['required'] ) && 'yes' === $property['required'] ) {
							$field_rules[ $property['name'] ] = array(
								'rules' => array(
									array(
										'type'   => 'empty',
										'prompt' => strip_tags( ! empty( $property['requiredMsg'] ) ? $property['requiredMsg'] : ( $property['title'] . '을(를) 입력하세요.' ) )
									)
								)
							);
						}

						if ( 'yes' == $property['certification'] ) {
							$field_rules[ $property['name'] . '_certification_number' ] = array(
								'rules' => array(
									array(
										'type'   => 'empty',
										'prompt' => __( '휴대폰 인증을 진행해주세요', 'mshop-members-s2' )
									)
								)
							);
						}
					} else if ( ! empty( $property['required'] ) && 'yes' === $property['required'] ) {
						if ( has_filter( 'msm_field_rule_' . $element['type'] ) ) {
							$field_rules = apply_filters( 'msm_field_rule_' . $element['type'], $field_rules, $element );
						} else {
							if ( 'Address' == $element['type'] && ! function_exists( 'MSADDR' ) ) {
								continue;
							}

							$rules = array(
								array(
									'type'   => in_array( $element['type'], array(
										'Toggle',
										'Quiz'
									) ) ? 'checked' : 'empty',
									'prompt' => strip_tags( ! empty( $property['requiredMsg'] ) ? $property['requiredMsg'] : ( $property['title'] . '을(를) 입력하세요.' ) )
								)
							);

							if ( ! empty( trim( msm_get( $property, 'regExp' ) ) ) ) {
								$rules[] = array(
									'type'   => 'regExp[' . msm_get( $property, 'regExp' ) . ']',
									'prompt' => msm_get( $property, 'regExpMsg' )
								);
							}

							$field_rules[ $property['name'] ] = array(
								'rules' => $rules
							);
						}
					}
				}
			}

			return apply_filters( 'msm_get_field_rules', $field_rules, $formdata );
		}

		public static function get_conditional_rules( $formdata ) {
			$conditional_rules = array();
			if ( ! empty( $formdata ) ) {
				foreach ( $formdata as $element ) {
					$property = $element['property'];

					if ( 'FormField' === $element['type'] ) {
						$conditional_rules = array_merge( $conditional_rules, self::get_conditional_rules( $property['items'] ) );
					} else if ( ! empty( $property['showIf'] ) ) {
						foreach ( $property['showIf'] as $condition ) {
							$conditional_rules[ $condition['id'] ] = $condition['value'];
						}
					}
				}
			}

			return $conditional_rules;
		}
		static function enqueue_script() {
			wp_enqueue_style( 'jquery-confirm', plugins_url( '/assets/vendor/jquery-confirm/jquery-confirm.min.css', MSM_PLUGIN_FILE ), array(), MSM_VERSION );
			wp_enqueue_script( 'jquery-confirm', plugins_url( '/assets/vendor/jquery-confirm/jquery-confirm.min.js', MSM_PLUGIN_FILE ), array( 'jquery' ), MSM_VERSION );

			wp_enqueue_script( 'semantic-ui', MSM()->plugin_url() . '/assets/vendor/semantic/semantic.min.js', array( 'jquery', 'jquery-ui-core', 'underscore' ), MSM_VERSION );
			wp_enqueue_script( 'mshop-members-form', MSM()->plugin_url() . '/assets/js/mshop-members-form' . ( msm_is_ie9() ? '-ie9' : '' ) . '.js', array( 'jquery', 'jquery-ui-core', 'underscore' ), MSM_VERSION );
			wp_enqueue_script( 'moment', MSM()->plugin_url() . '/assets/vendor/moment/moment.min.js', array(), MSM_VERSION );
			wp_enqueue_script( 'semantic-ui-calendar', MSM()->plugin_url() . '/assets/vendor/semantic-ui-calendar/calendar.js', array( 'jquery', 'jquery-ui-core', 'moment', 'semantic-ui' ), MSM_VERSION );

			wp_enqueue_style( 'msm-semantic-css', MSM()->plugin_url() . '/assets/vendor/semantic/semantic.min.css', array(), MSM_VERSION );
			if ( apply_filters( 'msm_enqueue_font_awesome', true ) ) {
				wp_enqueue_style( 'msm-font-awesome', MSM()->plugin_url() . '/assets/font-awesome/css/font-awesome.min.css', array(), MSM_VERSION );
			}
			wp_enqueue_style( 'msm-form-style', MSM()->plugin_url() . '/assets/css/mshop-members-form.css', array(), MSM_VERSION );
			wp_enqueue_style( 'msm-semantic-calendar-css', MSM()->plugin_url() . '/assets/vendor/semantic-ui-calendar/calendar.min.css', array(), MSM_VERSION );

			wp_enqueue_script( 'msm-crypto', plugins_url( 'assets/vendor/crypto/md5.js', MSM_PLUGIN_FILE ), array(), MSHOP_MEMBERS_VERSION );

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				wp_localize_script( 'mshop-members-form', '_msm', array(
					'ajaxurl'     => admin_url( "admin-ajax.php?lang=" . ICL_LANGUAGE_CODE ),
					'slug'        => MSM()->slug(),
					'msm_is_ajax' => defined( 'DOING_AJAX' ),
					'is_mobile'   => wp_is_mobile(),
					'_wpnonce'    => wp_create_nonce( 'mshop-members-s2' )
				) );

			} else {
				wp_localize_script( 'mshop-members-form', '_msm', array(
					'ajaxurl'     => admin_url( 'admin-ajax.php', 'relative' ),
					'slug'        => MSM()->slug(),
					'msm_is_ajax' => defined( 'DOING_AJAX' ),
					'is_mobile'   => wp_is_mobile(),
					'_wpnonce'    => wp_create_nonce( 'mshop-members-s2' )
				) );
			}

			if ( apply_filters( 'msm_print_styles', true ) ) {
				if ( apply_filters( 'msm_enqueue_font_awesome', true ) ) {
					wp_print_styles( array( 'msm-semantic-css', 'msm-font-awesome', 'msm-form-style', 'msm-semantic-calendar-css' ) );
				} else {
					wp_print_styles( array( 'msm-semantic-css', 'msm-form-style', 'msm-semantic-calendar-css' ) );
				}
			}
		}
		public static function mshop_form_designer( $attrs ) {
			$form = null;
			if ( function_exists( 'is_checkout' ) && apply_filters( 'msm_skip_on_checkout', is_checkout() ) ) {
				return '';
			}

			if ( ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) || ( function_exists( 'wp_is_jsonp_request' ) && wp_is_jsonp_request() ) ) {
				return '';
			}

			if ( ! empty( $_REQUEST['elementor-preview'] ) ) {
				return '';
			}

			$params = shortcode_atts( array(
				'id'                  => '',
				'slug'                => '',
				'name'                => '',
				'default'             => false,
				'top_message'         => 'yes',
				'bottom_message'      => 'no',
				'error_popup'         => 'no',
				'return_after_submit' => 'no',
				'social_redirect'     => '',
			), $attrs );

			if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$ajaxurl = admin_url( "admin-ajax.php?lang=" . ICL_LANGUAGE_CODE );
			} else {
				$ajaxurl = admin_url( 'admin-ajax.php', 'relative' );
			}

			// Get Form Data
			if ( ! empty( $params['id'] ) ) {
				$form = MSM_Manager::get_form( $params['id'] );
			} else if ( ! empty( $params['slug'] ) ) {
				$form = MSM_Manager::get_form_by_slug( $params['slug'] );
			}

			if ( is_null( $form ) ) {
				return '';
			}

			$formdata = $form->form_data;

			if ( 'StepContainer' === $formdata[0]['type'] ) {
				return self::mshop_form_designer_step_container( $formdata[0] );
			}

			if ( apply_filters( 'msm_check_pre_conditions', true, $form ) ) {
				if ( ! empty( $params['social_redirect'] ) ) {
					if ( 'referer' != $params['social_redirect'] ) {
						set_transient( 'msm_oauth_redirect_url_' . msm_get_state(), $params['social_redirect'], 3 * MINUTE_IN_SECONDS );
					} else if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
						set_transient( 'msm_oauth_redirect_url_' . msm_get_state(), $_SERVER['HTTP_REFERER'], 3 * MINUTE_IN_SECONDS );
					} else {
						delete_transient( 'msm_oauth_redirect_url_' . msm_get_state() );
					}
				} else {
					delete_transient( 'msm_oauth_redirect_url_' . msm_get_state() );
				}

				$form_types = null;
				// Enqueue script and styles
				self::enqueue_script();

				$post = null;
				if ( ! empty( $_REQUEST['post'] ) ) {
					$post = get_post( $_REQUEST['post'] );
				}

				$post = apply_filters( 'msm_post_data', $post, $form );

				$field_rules       = self::get_field_rules( $formdata );
				$conditional_rules = self::get_conditional_rules( $formdata );
				$form_categories   = get_the_terms( $form->id, 'mshop_members_form_cat' );
				if ( ! empty( $form_categories ) ) {
					$form_types = wp_list_pluck( $form_categories, 'slug' );
				}

				ob_start();

				do_action( 'mfd_before_output_forms_' . $form->submit_action, $form );

				if ( ! empty( $form_types ) ) {
					foreach ( $form_types as $form_type ) {
						do_action( 'msm_output_form_' . $form_type );
					}
				}

				$redirect_url = '';
				if ( ! empty( $_REQUEST['redirect_url'] ) ) {
					$redirect_url = $_REQUEST['redirect_url'];
				} else if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_url = $_REQUEST['redirect_to'];
				} else if ( 'yes' == $params['return_after_submit'] && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
					$redirect_url = $_SERVER['HTTP_REFERER'];
				}

				$form_classes = apply_filters( 'msm_form_classes', array( 'ui', 'form' ), $form );
				$redirect_url = apply_filters( 'msm_form_redirect_url', $redirect_url );

				if ( ! empty( $redirect_url ) && apply_filters( 'msm_use_safe_redirect', true ) ) {
					$redirect_url = wp_validate_redirect( wp_sanitize_redirect( $redirect_url ), apply_filters( 'wp_safe_redirect_fallback', admin_url(), 302 ) );
				}

				?>
                <script>
                    var mfs_form_<?php echo $form->id; ?> = {
                        ajaxurl: '<?php echo $ajaxurl; ?>',
                        fieldRules: '<?php echo json_encode( $field_rules ); ?>',
                        redirectURL: '<?php echo $redirect_url; ?>',
                        msm_nonce: '<?php echo wp_create_nonce( "mshop-members-s2-" . $form->id ); ?>',
                    };

					<?php if( $params['default'] ) : ?>
                    setTimeout( function () {
                        jQuery( '#mshop_members_popup' ).css( 'display', 'block' );
                    } );
					<?php endif; ?>
                </script>

                <div id="<?php echo 'mshop_form_' . $form->id; ?>" class="ui mfs_form <?php echo $form->custom_classes; ?>" style="<?php echo $params['default'] ? '' : 'display: none'; ?> <?php echo str_replace( "\n", " ", $form->custom_style ); ?>">
                    <style>
                        <?php echo $form->custom_css; ?>
                    </style>
					<?php if ( 'yes' == $params['top_message'] ) : ?>
                        <div class="mshop-members-message"><?php do_action( 'msm_form_notification', $form ); ?></div>
					<?php endif; ?>
                    <form class="<?php echo implode( ' ', $form_classes ); ?>" data-id="<?php _e( $form->id ); ?>"
                          data-slug="<?php echo $form->get_slug(); ?>"
                          data-error_popup="<?php echo $params['error_popup']; ?>"
                          data-type="<?php echo is_array( $form_types ) ? implode( ',', $form_types ) : ''; ?>"
                          onsubmit="return false">
						<?php

						do_action( 'before_output_form_' . $form->get_slug(), $form );

						foreach ( $formdata as $element ) {
							mfd_output( $element, $post, $form );
						}

						if ( 'register' == $form->form_type || 'upgrade_request' == $form->form_type ) {
							echo '<input type="hidden" name="mshop_form_id"     value="' . $form->id . '">';
							do_action( 'mshop_members_register_form' );
						}

						do_action( 'after_output_form_' . $form->get_slug(), $form );

						do_action( 'mfd_output_forms_' . $form->submit_action, $form );

						do_action( 'mfd_output_forms', $form );
						?>
						<?php if ( 'no' == $params['error_popup'] ) : ?>
                            <div class="ui error message"></div>
						<?php endif; ?>
                        <input type="hidden" name="_msm_postid" value="<?php echo $post && is_a( $post, 'WP_Post' ) ? $post->ID : ''; ?>">
                    </form>
					<?php if ( 'yes' == $params['bottom_message'] ) : ?>
                        <div class="mshop-members-message"><?php do_action( 'msm_form_notification', $form ); ?></div>
					<?php endif; ?>
                </div>
				<?php

				return ob_get_clean();
			}

		}

		public static function mshop_terms_for_customer() {
			ob_start();
			if ( is_user_logged_in() ) {
				load_template( MSM()->template_path() . '/terms/customer.php' );
			} else {
				load_template( MSM()->template_path() . '/terms/guest.php' );
			}
			echo ob_get_clean();
		}
		public static function email_authentication( $attrs, $content = null ) {
			wp_enqueue_style( 'msm-email-authentication', MSM()->plugin_url() . '/assets/css/email-authentication.css' );

			if ( is_user_logged_in() ) {
				if ( 'yes' == get_user_meta( get_current_user_id(), 'msm_email_certified', true ) ) {
					ob_start();

					msm_get_template( 'myaccount/email-certified.php', array() );

					return ob_get_clean();
				}
			}

			if ( isset( $_REQUEST['key'] ) && isset( $_REQUEST['login'] ) ) {
				if ( MSM_Email_Authenticate::process_authentication( $_REQUEST['login'], $_REQUEST['key'] ) ) {
					ob_start();

					msm_get_template( 'myaccount/email-certified.php', array() );

					return ob_get_clean();
				} else {
					ob_start();

					msm_get_template( 'myaccount/email-authentication-fail.php', array() );

					return ob_get_clean();
				}
			}

			if ( is_user_logged_in() ) {
				if ( isset( $_REQUEST['re-send'] ) ) {
					$user = get_user_by( 'id', get_current_user_id() );
					MSM_Email_Authenticate::send_authentication_email( $user );
				}
				ob_start();

				msm_get_template( 'myaccount/email-authentication.php', array() );

				return ob_get_clean();
			}
		}
	}

	MSM_Shortcodes::init();

endif;