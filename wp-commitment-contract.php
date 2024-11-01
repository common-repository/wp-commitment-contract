<?php
/*
Plugin Name: WP Commitment Contract
Description: Set personal goals and commitments and easily publish them on the immutable Ethereum blockchain for the whole world to see.
Version: 1.1.0
Author: web3 devs
Author URI: https://web3devs.com
License: GPLv2 or later
*/

function commitment_contract_goals_settings_display() {
	wp_register_script( 'web3-script', plugins_url( '/js/web3.min.js', __FILE__ ) );
	wp_register_script( 'goals-script', plugins_url( '/js/commitment-contract.js', __FILE__ ) );
	
	wp_enqueue_script( 'web3-script' );
	wp_enqueue_script( 'goals-script' );
	
	if (isset($_POST['newgoal']) && (strlen($_POST['newgoal']) > 5)) {
		check_admin_referer( 'goals-nonce' );
		
		$values = get_option('goals-array');
		
		if (!$values) {
			$values = array();
		}
		
		$values[] = sanitize_text_field($_POST['newgoal']);
				
        update_option('goals-array', $values);
    }

    $value = esc_textarea(get_option('coinhive_sitekey'));
    $threads = esc_textarea(get_option('coinhive_threads'));
	
    echo '<h1>Add a Goal</h1>';
    echo '<form method="POST" id="formSubmit">';
    wp_nonce_field( 'goals-nonce' );
	echo '<textarea id="newgoal" name="newgoal" placeholder="Ex: I commit to losing 10 pounds by January." style="width:50%" rows="10"></textarea>';
    echo '<br /><input type="submit" value="Put it on the blockchain!" class="button button-primary button-large" onclick="submitGoal();return false;">';
    echo '</form>';
    echo '<h1>Current Goals</h1>';
    echo '<ul>';
    $values = get_option('goals-array');
	if ($values) {
    	foreach ($values as $goal) {
	    	echo '<li>'.$goal.'</li>';
    	}
    }
    echo '</ul>';
}

function commitment_contract_goals_settings_create() {
    add_menu_page( 'Goals', 'Goals', 'manage_options', 'goals_settings', 'commitment_contract_goals_settings_display', '');
}
add_action('admin_menu', 'commitment_contract_goals_settings_create');

class Commitment_Contract_Goal_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'Commitment_Contract_Goal_Widget',
			'description' => 'Let the world see the goals you\'ve encoded in the blockchain',
		);
		parent::__construct( 'Commitment_Contract_Goal_Widget', 'Goal Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo '<h2>I have put these goals into the immutable Ethereum blockchain:</h2>';
		echo '<ul>';
		$values = get_option('goals-array');
		if ($values) {
    		foreach ($values as $goal) {
	    		echo '<li>'.$goal.'</li>';
    		}
    	}
		echo '</ul>';
		echo '<small><em>What does this mean? <a href="">Find out more</a></em></small>';
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'Commitment_Contract_Goal_Widget' );
});
