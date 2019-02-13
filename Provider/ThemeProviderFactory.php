<?php

namespace Harmony\Bundle\ThemeBundle\Provider;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Provider\DoctrineOrmSettingsProvider;
use Helis\SettingsManagerBundle\Provider\Factory\ProviderFactoryInterface;
use Helis\SettingsManagerBundle\Provider\SettingsProviderInterface;
use Helis\SettingsManagerBundle\Provider\SimpleSettingsProvider;
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
        $data = [];
        // TODO: make something cleaner
        if (null !== $this->theme && $this->kernel instanceof AbstractKernel) {
            if ((null !== $theme = $this->kernel->getThemes()[$this->theme->getData()] ?? null) &&
                $theme->hasSettings()) {
                $path     = implode('/', array_slice(explode(DIRECTORY_SEPARATOR, $theme->getPath()), - 2, 2));
                // TODO: get full path from ThemeInterface directly
                $filepath = $this->kernel->getThemeDir() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR .
                    'settings.yaml';
                // TODO: return parsed YAML from ThemeInterface???
                $data     = Yaml::parseFile($filepath);
                $data     = $data['settings'];
            }
        }
        // TODO: make something cleaner and safer
        $settings = $this->serializer->denormalize($data, SettingModel::class . '[]');

        return new SimpleSettingsProvider($settings);
    }
}