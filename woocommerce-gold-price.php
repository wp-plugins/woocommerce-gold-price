<?php
/**
 * Plugin Name: WooCommerce Gold Price
 * Plugin URI: http://omniwp.com.br/plugins/woocommerce-gold-price/
 * Description: Adds a Gold Price for 22k/24k gold products, making easy to update their prices
 * Version: 1.0
 * Author: omniWP
 * Author URI: http://omniwp.com.br
 * Requires at least: 3.0
 * Tested up to: 3.5
 *
 * Text Domain: woocommerce-gold-price
 * Domain Path: /languages/
 *
 * @package WooCommerce-Gold-Price
 * @category Core
 * @author omniWP
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'plugins_loaded', 'woocommerce_gold_price', 20);
add_filter( 'plugin_action_links', 'woocommerce_gold_price_action_links', 10, 2 );


function woocommerce_gold_price() {
	if ( ! class_exists( 'Woocommerce' ) ) {
		return false;		
	}

	add_action( 'admin_init', 'woocommerce_gold_price_admin_init' );
	add_action( 'admin_menu', 'woocommerce_gold_price_admin_menu', 10);
	
   load_plugin_textdomain( 'woocommerce-gold-price', false, '/woocommerce-gold-price/languages' );

	
	function woocommerce_gold_price_admin_init() {
		global $weight_unit, $weight_unit_description;

//		register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting( 'woocommerce_gold_price_options', 
			'woocommerce_gold_price_options', 
			'woocommerce_gold_price_validate_options' );

//		add_settings_section( $id, $title, $callback, $page );
    	add_settings_section( 'woocommerce_gold_price_plugin_options_section', 
			__('Gold Price Values', 'woocommerce-gold-price'), 
			'woocommerce_gold_price_fields', 
			'woocommerce_gold_price' );
			
//		add_settings_field( $id, $title, $callback, $page, $section, $args );
		add_settings_field( 'woocommerce_gold_price_options',
			__('Gold Price Values', 'woocommerce-gold-price'),
			'woocommerce_gold_price_fields',
			'woocommerce_gold_price_plugin_options_section',
			'woocommerce_gold_price' );			
		$weight_unit = get_option('woocommerce_weight_unit');
		$weight_unit_description = array(
			'kg'  => __( 'kg', 'woocommerce' ),
			'g'   => __( 'g', 'woocommerce' ),
			'lbs' => __( 'lbs', 'woocommerce' ),
			'oz' => __( 'oz', 'woocommerce' ) );
			
	}

	function woocommerce_gold_price_admin_menu() {
		global $menu, $woocommerce;
		if ( current_user_can( 'manage_woocommerce' ) ) {
			woocommerce_admin_css();
/*
		//	add_submenu_page( $parent_slug, 
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$function
*/
			add_submenu_page('woocommerce',
				 __('Gold Price', 'woocommerce-gold-price'),  
				 __('Gold Price', 'woocommerce-gold-price') , 
				 'manage_woocommerce_products', 
				 'woocommerce_gold_price', 
				 'woocommerce_gold_price_page');
		}
	}
	

	function woocommerce_gold_price_page() {
		global $weight_unit, $weight_unit_description;
		if ( ! isset( $_REQUEST['settings-updated'] ) ) {
			$_REQUEST['settings-updated'] = false; 
		} 
?>

<div class="wrap woocommerce">
  <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
  <h2><?php _e('Gold Price', 'woocommerce-gold-price')?></h2>
<?php
		if ( false !== $_REQUEST['settings-updated'] ) { 
?>
    <div id="message" class="updated fade">
		<p><strong><?php _e( 'Your settings have been saved.', 'woocommerce' ) ?></strong></p>
	</div>
<?php
		}
?>

  <form method="post" action="options.php">
    <?php
		settings_fields( 'woocommerce_gold_price_options' );
		do_settings_sections( 'woocommerce_gold_price' );
?>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
    </p>
  </form>
	<h2><?php _e( 'Gold priced products', 'woocommerce-gold-price' )?></h2>
<?php
		$options = get_option( 'woocommerce_gold_price_options' );
		foreach( $options as $key => $value ) {
	?>
	<h3><?php echo $key ?></h3>
	<ol>
	<?php
			$the_query = new WP_Query( array( 'post_type' => 'product', 'meta_key' => 'karat', 'meta_value' => substr( $key, 0, -1) ) ); // meta value = 24 or 22
			// The Loop
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
				$the_product = new WC_Product( $the_query->post->ID );
				$edit_url    = admin_url( 'post.php?post=' . $the_product->id . '&action=edit' );
				$message     = '';
				echo '
		<li><a href="' . $edit_url. '">' . get_the_title(). '</a>';
				if ( ! $the_product->has_weight() ) {
					$message = __( 'Product has zero weight, can\'t calculate price based on weight', 'woocommerce-gold-price' );
				} else {
					$the_product->regular_price = $the_product->weight * $value;
					if ( $the_product->is_on_sale() ) {
						$message = __( 'Product was on sale, can\'t calculate sale price', 'woocommerce-gold-price' );
					}
					echo ': ' . $the_product->weight . $weight_unit_description[ $weight_unit ] . ' * ' . woocommerce_price( $options['24k'] )  . ' = ' . woocommerce_price( $the_product->regular_price );
					if ( false !== $_REQUEST['settings-updated'] ) {
						update_post_meta( $the_product->id, '_price',         $the_product->regular_price );
						update_post_meta( $the_product->id, '_regular_price', $the_product->regular_price );
						update_post_meta( $the_product->id, '_sale_price', '' );
						update_post_meta( $the_product->id, '_sale_price_dates_from', '' );
						update_post_meta( $the_product->id, '_sale_price_dates_to', '' );
					}
				}
				echo ' ' . $message . '</li>';			
			endwhile;
			// Restore original Query & Post Data
			wp_reset_query();
			wp_reset_postdata();
	?>
	</ol>
	<?php
			}
?>			
</div>
<?php
	}

	function woocommerce_gold_price_fields() {
		global $weight_unit, $weight_unit_description;

		$options = get_option( 'woocommerce_gold_price_options' );
		$currency_pos = get_option('woocommerce_currency_pos');
		$currency_symbol = get_woocommerce_currency_symbol();

?>
	<table class="form-table widefat">
	  <thead>
	  	<tr valign="top">
			<th scope="col"><?php _e( 'Karats', 'woocommerce-gold-price' )?></th>
			<th scope="col"><?php _e( 'Price', 'woocommerce' ) ?></td>	
			<th scope="col"><?php _e( 'Weight Unit', 'woocommerce' ) ?></td>	
	  	</tr>
	  </thead>
	
	  <tr valign="top">
		<th scope="row"><label for="woocommerce_gold_price_options_24">24k</label></th>
		<td>
<?php
		$input = '<input id="woocommerce_gold_price_options_24" name="woocommerce_gold_price_options[24k]" size="10" type="text" value="' . $options['24k'] . '" />';
		switch ($currency_pos) {
			case 'left' :
				echo $currency_symbol . $input;
			break;
			case 'right' :
				echo $input . $currency_symbol;
			break;
			case 'left_space' :
				echo $currency_symbol . '&nbsp;' . $input;
			break;
			case 'right_space' :
				echo $input . '&nbsp;' . $currency_symbol;
			break;
		}
?>		
		</td>
		<td><?php echo $weight_unit_description[ $weight_unit ] ?></td>
	  </tr>
	  <tr valign="top" class="alternate">
		<th scope="row"><label for="woocommerce_gold_price_options_22">22k</label></th>
		<td>
<?php
		$input = '<input id="woocommerce_gold_price_options_22" name="woocommerce_gold_price_options[22k]" size="10" type="text" value="' . $options['22k'] . '" />';
		switch ($currency_pos) {
			case 'left' :
				echo $currency_symbol . $input;
			break;
			case 'right' :
				echo $input . $currency_symbol;
			break;
			case 'left_space' :
				echo $currency_symbol . '&nbsp;' . $input;
			break;
			case 'right_space' :
				echo $input . '&nbsp;' . $currency_symbol;
			break;
		}
?>		
		</td>
		<td><?php echo $weight_unit_description[ $weight_unit ] ?></td>
	  </tr>
	</table>
<?php
	}
	

	function woocommerce_gold_price_validate_options( $input ) {
		foreach ( $input as $key =>$value ) {
			$input[$key] =  wp_filter_nohtml_kses($value);
		}
		return $input;
	}

	// Display a Settings link on the main Plugins page
	function woocommerce_gold_price_action_links( $links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			$woocommerce_gold_price_settings_link = '<a href="'.get_admin_url().'admin.php?page=woocommerce_gold_price">'.__('Settings').'</a>';
			// make the 'Settings' link appear first
			array_unshift( $links, $woocommerce_gold_price_settings_link );
		}
		return $links;
	}	
}
?>