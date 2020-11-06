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

namespace Pressmodo\CLI;

use Imagecow\Image;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use WP_CLI;

/**
 * Blur Command
 */
class BlurifyCommand {

	/**
	 * Blur all images under the wp-content/uploads folder.
	 * By Default the command, makes a backup of the uploads folder before proceeding with the blurring.
	 *
	 * ## EXAMPLE
	 *
	 *  $ wp blurify blur
	 *  Blurall images under the wp-content/uploads folder.
	 *
	 *  $ wp blurify blur --backup=false
	 *  Blur all images under the wp-content/uploads folder and disables backups.
	 *
	 * @param array $args arguments of the command
	 * @param array $assocArgs associative arguments of the command
	 * @return void
	 */
	public function blur( $args = [], $assocArgs = [] ) {

		$uploadsPath         = wp_upload_dir();
		$uploadsPath         = $uploadsPath['basedir'];
		$uploadsPathCopyPath = trailingslashit( WP_CONTENT_DIR ) . 'uploads_copy';

		$makeBackup = true;

		if ( isset( $assocArgs['backup'] ) && $assocArgs['backup'] === 'false' ) {
			$makeBackup = false;
		}

		$filesystem = new Filesystem();

		if ( $makeBackup ) {
			$filesystem->remove( [ $uploadsPathCopyPath ] );

			WP_CLI::line( '' );
			WP_CLI::line( 'Removed any previous backup.' );

			try {

				$filesystem->mirror( $uploadsPath, $uploadsPathCopyPath );

				WP_CLI::line( 'Created backup folder.' );
				WP_CLI::line( '' );

			} catch ( IOExceptionInterface $exception ) {
				WP_CLI::error( $exception->getMessage() );
			}
		} else {
			WP_CLI::line( '' );
			WP_CLI::warning( 'Uploads folder backup disabled.' );
			WP_CLI::line( '' );
		}

		$images = $this->getImagesList( $uploadsPath );

		if ( count( $images ) <= 0 || ! is_array( $images ) ) {
			WP_CLI::error( 'No images have been found.' );
		}

		$notify = \WP_CLI\Utils\make_progress_bar( sprintf( 'Processing %s images', count( $images ) ), count( $images ) );

		foreach ( $images as $image ) {

			$newImage = Image::fromFile( $image )
				->blur( 2 )
				->save();

			$notify->tick();

		}

		$notify->finish();

		\WP_CLI::line( '' );
		\WP_CLI::success( 'Done.' );
		\WP_CLI::line( '' );

	}

	/**
	 * Get list of all images into the uploads folder.
	 *
	 * @param string $path the uploads folder.
	 * @return array
	 */
	private function getImagesList( $path ) {

		$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) );

		$files = [];

		foreach ( $iterator as $file ) {

			if ( $file->isDir() ) {
				continue;
			}

			$path    = $file->getPathname();
			$isImage = exif_imagetype( $path );

			if ( $isImage === false ) {
				continue;
			}

			$files[] = $path;

		}

		return $files;

	}

}
