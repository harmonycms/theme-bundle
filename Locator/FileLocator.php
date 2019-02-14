<?php

namespace Harmony\Bundle\ThemeBundle\Locator;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Liip\ThemeBundle\Locator\FileLocator as BaseFileLocator;

/**
 * Class FileLocator
 *
 * @package Harmony\Bundle\ThemeBundle\Locator
 */
class FileLocator extends BaseFileLocator
{

    /**
     * Locate Resource Theme aware. Only working for app/Resources.
     *
     * @param string $name
     * @param string $dir
     * @param bool   $first
     *
     * @return string|array
     */
    protected function locateAppResource($name, $dir = null, $first = true)
    {
        /** @var AbstractKernel $kernel */
        $kernel = $this->kernel;
        if ($this->kernel instanceof AbstractKernel) {
            $themeName = $this->activeTheme->getName();
            if (isset($kernel->getThemes()[$themeName])) {
                $theme           = $kernel->getThemes()[$themeName];
                $this->lastTheme = $theme->getShortName();
            }
        }

        return parent::locateAppResource($name, $dir, $first);
    }
}