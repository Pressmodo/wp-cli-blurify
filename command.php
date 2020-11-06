<?php
/**
 * Setup the wp-cli command.
 *
 * @package   wp-cli-blurify
 * @author    Alessandro Tesoro <hello@pressmodo.com>
 * @copyright 2020 Alessandro Tesoro
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://pressmodo.com
 */

use Pressmodo\CLI\BlurifyCommand;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

\WP_CLI::add_command( 'blurify', BlurifyCommand::class );
