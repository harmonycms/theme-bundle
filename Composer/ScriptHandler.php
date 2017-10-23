<?php

namespace Harmony\Bundle\ThemeBundle\Composer;

use Composer\Script\Event;
use Harmony\Bundle\CoreBundle\Composer\AbstractScriptHandler;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as DistributionBundleScriptHandler;

/**
 * Class ScriptHandler
 *
 * @package Harmony\Bundle\ThemeBundle\Composer
 */
class ScriptHandler extends AbstractScriptHandler
{

    /**
     * Occurs after the install command has been executed with a lock file present.
     * Occurs before the update command is executed, or before the install command is executed without a lock file
     * present.
     *
     * @param Event $event
     */
    public static function handleCommandScripts(Event $event)
    {
        DistributionBundleScriptHandler::installAssets($event);
        self::installAssets($event);
    }

    /**
     * Installs the themes assets under the web root directory.
     * For better interoperability, assets are copied instead of symlinked by default.
     * Even if symlinks work on Windows, this is only true on Windows Vista and later,
     * but then, only when running the console with admin rights or when disabling the
     * strict user permission checks (which can be done on Windows 7 but not on Windows
     * Vista).
     *
     * @param Event $event
     */
    public static function installAssets(Event $event)
    {
        $options    = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install themes assets');

        if (null === $consoleDir) {
            return;
        }

        $webDir  = $options['symfony-web-dir'];
        $symlink = '';
        if ('symlink' == $options['symfony-assets-install']) {
            $symlink = '--symlink ';
        } elseif ('relative' == $options['symfony-assets-install']) {
            $symlink = '--symlink --relative ';
        }

        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'install assets')) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'theme:assets:install ' . $symlink . escapeshellarg($webDir),
            $options['process-timeout']);
    }

}