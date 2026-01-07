<?php

declare(strict_types=1);

/*
 * This file is part of the WordPress mu-plugin "Bedrock Autoloader MU".
 *
 * (ɔ) Frugan <dev@frugan.it>
 *
 * This source file is subject to the GNU GPLv3 or later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace WpSpaghetti\BedrockAutoloader;

use Composer\InstalledVersions;

/**
 * Helper class to copy bedrock-autoloader.php to mu-plugins root.
 *
 * Usage in composer.json scripts:
 * "copy-bedrock-autoloader": [
 *     "WpSpaghetti\\BedrockAutoloader\\CopyHelper::copy"
 * ]
 */
class CopyHelper
{
    /**
     * Copy bedrock-autoloader.php to mu-plugins root directory.
     *
     * This method automatically detects the package installation path using Composer APIs
     * and copies the main autoloader file to the parent mu-plugins directory.
     *
     * @param null|string $customDestination Optional custom destination path
     */
    public static function copy(?string $customDestination = null): void
    {
        // Auto-detect package installation path using Composer API
        try {
            $packagePath = InstalledVersions::getInstallPath('wp-spaghetti/bedrock-autoloader-mu');

            if (null === $packagePath) {
                echo "✗ Error: Package wp-spaghetti/bedrock-autoloader-mu not found in installed packages\n";

                return;
            }
        } catch (\OutOfBoundsException $e) {
            echo '✗ Error: Package wp-spaghetti/bedrock-autoloader-mu not found: '.$e->getMessage()."\n";

            return;
        }

        $source = $packagePath.'/bedrock-autoloader.php';

        // Use custom destination or auto-detect parent directory
        if (null !== $customDestination) {
            $destination = rtrim($customDestination, '/').'/bedrock-autoloader.php';
        } else {
            // Copy to parent directory (mu-plugins root)
            $destination = \dirname($packagePath).'/bedrock-autoloader.php';
        }

        if (!file_exists($source)) {
            echo "✗ Error: Source file not found at {$source}\n";

            return;
        }

        $destinationDir = \dirname($destination);
        if (!is_dir($destinationDir)) {
            echo "✗ Error: Destination directory does not exist: {$destinationDir}\n";
            echo "   Package is installed at: {$packagePath}\n";
            echo "   Attempting to copy to: {$destination}\n";

            return;
        }

        if (!is_writable($destinationDir)) {
            echo "✗ Error: Destination directory is not writable: {$destinationDir}\n";

            return;
        }

        if (copy($source, $destination)) {
            echo "✓ Copied bedrock-autoloader.php to: {$destination}\n";
        } else {
            echo "✗ Failed to copy bedrock-autoloader.php\n";
        }
    }
}
