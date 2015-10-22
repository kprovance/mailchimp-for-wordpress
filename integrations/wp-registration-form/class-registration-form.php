<?php

defined( 'ABSPATH' ) or exit;

class MC4WP_Registration_Form_Integration extends MC4WP_User_Integration {

	/**
	 * @var string
	 */
	public $name = "Registration Form";

	/**
	 * @var string
	 */
	public $description = "Adds a sign-up checkbox to your site's registration form.";

	/**
	 * @var string
	 */
	public $slug = 'wp-registration-form';

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'login_head', array( $this, 'print_css_reset' ) );
		add_action( 'register_form', array( $this, 'output_checkbox' ), 20 );
		add_action( 'user_register', array( $this, 'subscribe_from_registration' ), 90, 1 );
	}

	/**
	 * Subscribes from WP Registration Form
	 *
	 * @param int $user_id
	 *
	 * @return bool|string
	 */
	public function subscribe_from_registration( $user_id ) {

		if( $this->is_honeypot_filled() ) {
			return false;
		}

		// was sign-up checkbox checked?
		if ( ! $this->checkbox_was_checked() ) {
			return false;
		}

		// gather emailadress from user who WordPress registered
		$user = get_userdata( $user_id );

		// was a user found with the given ID?
		if ( ! $user instanceof WP_User ) {
			return false;
		}

		$email = $user->user_email;
		$merge_vars = $this->user_merge_vars( $user );

		return $this->subscribe( $email, $merge_vars, $user_id );
	}
	/* End registration form functions */


	/**
	 * @return bool
	 */
	public function is_installed() {
		return true;
	}
}