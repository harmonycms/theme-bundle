<?php

namespace Harmony\Bundle\ThemeBundle\Services;

use Helis\SettingsManagerBundle\Settings\SettingsRouter;

/**
 * Determines the theme that should currently be used.
 *
 * @package Harmony\Bundle\ThemeBundle\Services
 */
class ThemeResolver
{

    /** @var SettingsRouter $settingsRouter */
    protected $settingsRouter;

    /**
     * ThemeResolver constructor.
     *
     * @param SettingsRouter $settingsRouter
     */
    public function __construct(SettingsRouter $settingsRouter)
    {
        $this->settingsRouter = $settingsRouter;
    }

    /**
     * Get current active theme from parameter: `harmony.theme`.
     *
     * @return null|string
     */
    public function getActiveTheme(): ?string
    {
        return $this->settingsRouter->get('theme', null);
    }
}