=== WooCommerce Gold Price Extension ===
Contributors: Gabriel Reguly
Donate link: http://omniwp.com.br/donate/
Tags: WooCommerce, Gold Price, Gold Based prices 
Requires at least:3.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds Gold Price extension to WooCommerce plugin, tested up to WooCommerce 2.0.8

== Description ==

### Add Gold Price to WooCommerce

This plugin enables easily changing prices of gold products, based on their weigth/purity and the gold value.

Please notice that WooCommerce must be installed and active.


== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
1. Activate the plugin
1. Insert/Edit a product in WooCommerce
1. Fill in its weight value
1. Add new/Use existing Custom Field named 'karat', with values 24, 22, 18 or 14 to indicate the purity of the gold  
1. Update the gold price at WooCommerce -> Gold Price and all products with 'karat' field will have their prices updated

== Screenshots ==

1. Custom Field 'karat' 
1. Updating gold values and product prices

== Frequently Asked Questions == 

= How many karats can be added/used? =

* Only 24k, 22k, 18k and 14k

= Do I need to calculate the price when adding a new gold product? =

* Only if you are not going to update the gold price.

= Can I have a sale price for gold product? =

* Yes, but the sale price will be removed when the gold price is updated.

= I see no products under "Gold priced products" ( WooCommerce -> Gold Price ) =

* This is because you have no gold products, e.g., products with Custom Field named karat and values 24, 22, 18 or 14.

= What "Product was on sale, can't calculate sale price" means? =

* Means that the product no longer is on sale, as the plugin can't calculate sale prices and just removed it. 
There is a handy link to edit the product, if one whishes to put it on sale again.

== Changelog ==
= 2.1 =
* Updated to be compatible with WooCommerce 2.1
= 1.0.3 =
* Fixed 'Product has zero weight, can't calculate price based on weight.' but product had weight
* Added 14k option.
* Improved message when there are no gold products.
= 1.0.2 =
* Fixed error 'You do not have sufficient permissions to access this page.'
* Added 18k option.
* Added message when there are no gold products.
= 1.0.1 =
* Fixed 'posts_per_page'		
= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 2.1 =
* Users of WooCommerce 2.1 must upgrade.
= 1.0.3 =
* All users must upgrade.
= 1.0.2 =
* All users must upgrade.
= 1.0.1 =
* All users must upgrade.
= 1.0 = 
* Enjoy it.