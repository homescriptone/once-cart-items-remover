<?php

/**
 * Plugin Name:       Once Cart Items Remover
 * Plugin URI:        https://homescriptone.com
 * Description:       An easy way to remove all items from the cart in one click.
 * Version:           1.0
 * Author:            HomeScript
 * Author URI:        https://homescriptone.com
 * Text Domain:       woorci
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

 if (!defined('ABSPATH')){
     die();
 }
 define('WOORCI_PLUGIN_VERSION','1.0.0');
 define('WOORCI_PLUGIN_URL',plugin_dir_url(__FILE__));

 add_action('woocommerce_cart_actions',function(){
     if (is_cart()){
         $woorci_options = get_option('woorci-options');
         if ( $woorci_options == "yes" ){ 
            ?>
                <button type="submit" class="button" name="remove_all_items" id="remove_all_items"><?php _e('Remove All Cart Items','woorci'); ?></button>
            <?php
         }
     }
 });

 add_action('wp_enqueue_scripts',function(){
    wp_enqueue_script('woorci-js', plugin_dir_url(__FILE__) . 'js/woorci.js', array('jquery'), WOORCI_PLUGIN_VERSION , false);
    wp_localize_script('woorci-js','woorci_ajax_object',
    [
        'woorci_ajax_url'      => admin_url('admin-ajax.php'),
        'woorci_ajax_security' => wp_create_nonce('woocir-ajax-security-nonce'),
    ]);
});

add_action('admin_enqueue_scripts',function(){
    wp_enqueue_style('woorci-css', plugin_dir_url(__FILE__) . 'css/woorci.css', array(), WOORCI_PLUGIN_VERSION , 'all');
});

add_action('wp_ajax_woorci_remove_all_items','woorci_remove_all_items');
add_action('wp_ajax_no_priv_woorci_remove_all_items','woorci_remove_all_items');
function woorci_remove_all_items(){
    $success = 0;
    if (isset($_POST['data']) && !wp_verify_nonce( $_POST['security'], 'woo-usn-ajax-nonce' ) ){
       $data = wp_unslash($_POST['data']);
       $starter = sanitize_text_field($data["woorci_remove"]);
       if ($starter == 0){
           global $woocommerce;
           $status = $woocommerce->cart->empty_cart();
           if ($status == null){
               $success = 1;
           }
       }
       echo esc_attr( $success );
    }
    wp_die();
}

add_filter( 'woocommerce_get_sections_products' , 'woorci_add_settings_tab' );
function woorci_add_settings_tab( $settings_tab ){
     $settings_tab['woorci'] = __( 'Once Cart Items Remover' , 'woorci' );
     return $settings_tab;
}

add_filter( 'woocommerce_get_settings_products' , 'woorci_get_settings' , 10, 2 );
function woorci_get_settings( $settings, $current_section ) {
         if( 'woorci' == $current_section ) {
              $settings =  array(
					[
					        'name' => __( 'Once Cart Items Remover','woorci' ),
					        'type' => 'title',
                    ],
                    [
                        'name' => __('Enable/Disable','woorci'),
                        'type' => 'checkbox',
                        'default' => 'checked',
                        'id' => 'woorci-options',
                        'desc' => __( 'Display button that allows your customer, to delete all items from the cart in one click.' , 'woorci' ),
                    ],
                    array( 'type' => 'sectionend', 'id' => 'woorci' ),
		    );
       } 
       return $settings;
}

add_action('admin_menu','woorci_add_submenu');
function woorci_add_submenu(){
	add_submenu_page( 'woocommerce', '', __('Once Cart Items Remover','woorci'),'manage_options', 'woorci','woorci_redirect_to_settings');
}

function woorci_redirect_to_settings(){
    wp_redirect(admin_url('admin.php?page=wc-settings&tab=products&section=woorci'));
    exit;
}

