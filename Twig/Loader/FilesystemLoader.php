<?php

namespace Harmony\Bundle\ThemeBundle\Twig\Loader;

use Helis\SettingsManagerBundle\Settings\SettingsRouter;
use Liip\ThemeBundle\Twig\Loader\FilesystemLoader as Base;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

/**
 * Class FilesystemLoader
 *
 * @package Harmony\Bundle\ThemeBundle\Twig\Loader
 */
class FilesystemLoader extends Base
{

    /** @var KernelInterface $kernel */
    protected $kernel;

    /** @var SettingsRouter $settingsRouter */
    protected $settingsRouter;

    /**
     * Constructor.
     *
     * @see TwigBundle own FilesystemLoader
     *
     * @param FileLocatorInterface        $locator  A FileLocatorInterface instance
     * @param TemplateNameParserInterface $parser   A TemplateNameParserInterface instance
     * @param KernelInterface             $kernel
     * @param SettingsRouter              $settingsRouter
     * @param string|null                 $rootPath The root path common to all relative paths (null for getcwd())
     */
    public function __construct(FileLocatorInterface $locator, TemplateNameParserInterface $parser,
                                KernelInterface $kernel, SettingsRouter $settingsRouter, ?string $rootPath = null)
    {
        parent::__construct($locator, $parser, $rootPath);
        $this->kernel         = $kernel;
        $this->settingsRouter = $settingsRouter;
    }

    /**
     * Returns the path to the template file.
     * The file locator is used to locate the template when the naming convention
     * is the symfony one (i.e. the name can be parsed).
     * Otherwise the template is located using the locator from the twig library.
     *
     * @param string|TemplateReferenceInterface $template The template
     * @param bool                              $throw    When true, a \Twig_Error_Loader exception will be thrown if a
     *                                                    template could not be found
     *
     * @return string The path to the template file
     * @throws \Twig_Error_Loader if the template could not be found
     */
    protected function findTemplate($template, $throw = true)
    {
        // Set active theme from database/settings
        if ($name = $this->settingsRouter->get('theme')) {
            $this->activeTheme->setName($name);
        }

        return parent::findTemplate($template, $throw);
    }
}