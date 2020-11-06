<?php
/**
 * Plugin Name:     WP CLI Blurify
 * Plugin URI:      https://sematico.com
 * Description:     Blur all images under the wp-content/uploads folder.
 * Author:          Alessandro Tesoro
 * Author URI:      https://sematico.com
 * Text Domain:     wp-cli-blurify
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * WP CLI Blurify is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP CLI Blurify is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP CLI Blurify. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package wp-cli-blurify
 * @author Sematico LTD
 */

namespace Pressmodo\CLI;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

require_once dirname( __FILE__ ) . '/command.php';
