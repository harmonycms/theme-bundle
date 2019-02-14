<?php

namespace Harmony\Bundle\ThemeBundle\Provider;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Harmony\Sdk\Theme\ThemeInterface;
use Helis\SettingsManagerBundle\Model\DomainModel;
use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Model\TagModel;
use Helis\SettingsManagerBundle\Provider\DoctrineOrmSettingsProvider;
use Helis\SettingsManagerBundle\Provider\Factory\ProviderFactoryInterface;
use Helis\SettingsManagerBundle\Provider\ReadableSimpleSettingsProvider;
use Helis\SettingsManagerBundle\Provider\SettingsProviderInterface;
use Helis\SettingsManagerBundle\Provider\Traits\ReadOnlyProviderTrait;
use LogicException;
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

    /** @var null|SettingModel $theme */
    protected $theme;

    /**
     * ThemeProviderFactory constructor.
     *
     * @param DenormalizerInterface       $serializer
     * @param DoctrineOrmSettingsProvider $provider
     * @param KernelInterface             $kernel
     */
    public function __construct(DenormalizerInterface $serializer, DoctrineOrmSettingsProvider $provider,
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
                $theme->hasSettings()) {
                $data = Yaml::parseFile($theme->getSettingPath());
                if (!isset($data['settings'])) {
                    throw new LogicException(sprintf('The root node "settings" is missing in the file %s',
                        $theme->getSettingPath()));
                }
                $data = $data['settings'];
            }
        }
        /** @var SettingModel[] $settings */
        $settings = $this->serializer->denormalize($data, SettingModel::class . '[]');
        if (!empty($settings)) {
            $this->_updateSettingsForTheme($settings, $theme);
        }

        return new ReadableSimpleSettingsProvider($settings);
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
        $themeDomain = (new DomainModel())->setName('theme')->setEnabled(true)->setReadOnly(true);
        $themeIdTag  = (new TagModel())->setName($theme->getIdentifier());

        /** @var SettingModel $setting */
        foreach ($settings as $setting) {
            // Set default domain to `theme`
            $setting->setDomain($themeDomain);
            if ($theme instanceof ThemeInterface) {
                $setting->getTags()->add($themeIdTag);
            }
        }
    }
}