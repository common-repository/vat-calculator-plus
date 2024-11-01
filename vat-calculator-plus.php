<?php
/*
Plugin Name: VAT Calculator Plus
Plugin URI: http://www.vatcalculatorplus.com/
Description: Add a VAT calculator to your WordPress site, using the functionality at the <a href="http://www.vatcalculatorplus.com">VAT Calculator Plus</a> web site.  You can set a fixed VAT rate or let your visitor set the rate.  Has a clean, modern look, and if you are a CSS guru, you can style it totally yourself.
Version: 1.0.0
Author: VAT Calculator Plus
Author URI: http://www.vatcalculatorplus.com/
License: GPLv2 or later
Text Domain: vatcalculatorplus
*/

/*  Copyright 2014  VATCalculatorPlus

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-vat-calculator-plus.php' );

VatCalculatorPlus::get_instance();
