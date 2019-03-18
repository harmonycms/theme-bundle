<?php

namespace Harmony\Bundle\ThemeBundle\Provider;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Harmony\Bundle\ThemeBundle\DependencyInjection\SettingsConfiguration;
use Harmony\Sdk\Theme\ThemeInterface;
use Harmony\Bundle\SettingsManagerBundle\Model\SettingDomain;
use Harmony\Bundle\SettingsManagerBundle\Model\Setting;
use Harmony\Bundle\SettingsManagerBundle\Model\SettingTag;
use Harmony\Bundle\SettingsManagerBundle\Provider\Factory\ProviderFactoryInterface;
use Harmony\Bundle\SettingsManagerBundle\Provider\ReadableSimpleSettingsProvider;
use Harmony\Bundle\SettingsManagerBundle\Provider\SettingsProviderInterface;
use Harmony\Bundle\SettingsManagerBundle\Provider\Traits\ReadOnlyProviderTrait;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ThemeProviderFactory
 *
 * @package Harmony\Bundle\ThemeBundle\Provider
 */
class ThemeProviderFactory implements ProviderFactoryInterface
{

    use ReadOnlyProviderTrait;

    /** @var DenormalizerInterface $serializer */
    protected $serializer;

    /** @var KernelInterface|AbstractKernel $kernel */
    protected $kernel;

    /** @var null|Setting $theme */
    protected $theme;

    /**
     * ThemeProviderFactory constructor.
     *
     * @param DenormalizerInterface     $serializer
     * @param SettingsProviderInterface $provider
     * @param KernelInterface           $kernel
     */
    public function __construct(DenormalizerInterface $serializer, SettingsProviderInterface $provider,
                                KernelInterface $kernel)
    {
        $this->serializer = $serializer;
        $theme            = $provider->getSettingsByName(['default'], ['theme']);
        $this->theme      = array_shift($theme);
        $this->kernel     = $kernel;
    }

    /**
     * @return SettingsProviderInterface
     * @throws ExceptionInterface
     */
    public function get(): SettingsProviderInterface
    {
        $data  = [];
        $theme = null;
        if (null !== $this->theme && $this->kernel instanceof AbstractKernel) {
            if ((null !== $theme = $this->kernel->getThemes()[$this->theme->getData()] ?? null) &&
                ($theme->hasSettings() ||
                    (null !== $parentTheme = $theme->getParent()) && $parentTheme->hasSettings())) {
                $settingPath = $theme->getSettingPath();
                if (isset($parentTheme) && $parentTheme->hasSettings()) {
                    $settingPath = $parentTheme->getSettingPath();
                }

                $configuration = new SettingsConfiguration();
                $data          = $this->processConfiguration($configuration, Yaml::parseFile($settingPath));
            }
        }
        /** @var Setting[] $settings */
        $settings = $this->serializer->denormalize($data, Setting::class . '[]');
        if (!empty($settings)) {
            $this->_updateSettingsForTheme($settings, $theme);
        }

        return new ReadableSimpleSettingsProvider($settings);
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array                  $configs
     *
     * @return array
     */
    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }

    /**
     * Override SettingModel values to be matched as a theme settings.
     * A theme setting is defined by:
     * - domain = theme
     * - tags contain the theme name
     *
     * @param array               $settings
     * @param ThemeInterface|null $theme
     *
     * @return void
     */
    private function _updateSettingsForTheme(array &$settings, ?ThemeInterface $theme): void
    {
        $themeDomain = (new SettingDomain())->setName('theme')->setEnabled(true)->setReadOnly(true);
        $themeIdTag  = (new SettingTag())->setName($theme->getIdentifier());

        /** @var Setting $setting */
        foreach ($settings as $setting) {
            // Set default domain to `theme`
            $setting->setDomain($themeDomain);
            if ($theme instanceof ThemeInterface) {
                $setting->getTags()->add($themeIdTag);
            }
        }
    }
}