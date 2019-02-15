<?php

namespace Harmony\Bundle\ThemeBundle\EventListener;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Harmony\Bundle\MenuBundle\Event\ConfigureMenuEvent;
use Harmony\Bundle\MenuBundle\Menu\ItemInterface;
use Harmony\Bundle\ThemeBundle\DependencyInjection\MenuConfiguration;
use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Provider\DoctrineOrmSettingsProvider;
use Knp\Menu\ItemInterface as KnpItemInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MenuListener
 *
 * @package Harmony\Bundle\ThemeBundle\EventListener
 */
class MenuListener
{

    /** @var AbstractKernel|KernelInterface $kernel */
    protected $kernel;

    /** @var null|SettingModel $theme */
    protected $theme;

    /**
     * MenuListener constructor.
     *
     * @param KernelInterface|AbstractKernel $kernel
     * @param DoctrineOrmSettingsProvider    $provider
     */
    public function __construct(KernelInterface $kernel, DoctrineOrmSettingsProvider $provider)
    {
        $this->kernel = $kernel;
        $theme        = $provider->getSettingsByName(['default'], ['theme']);
        $this->theme  = array_shift($theme);
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $data = [];
        if ('admin' !== $menu->getDomain()->getName() && null !== $this->theme &&
            $this->kernel instanceof AbstractKernel) {
            if ((null !== $theme = $this->kernel->getThemes()[$this->theme->getData()] ?? null) && $theme->hasMenu()) {
                $configuration = new MenuConfiguration();
                $data          = $this->processConfiguration($configuration, Yaml::parseFile($theme->getMenuPath()));
            }
        }
        /** @var ItemInterface|KnpItemInterface $menu */
        $menuUpdate = function ($menu) use ($data, &$menuUpdate) {
            $menu->setChildrenAttributes(array_merge($menu->getChildrenAttributes(), $data['childrenAttributes']));
            $menu->setAttributes(array_merge($menu->getAttributes(), $data['itemAttributes']));
            $menu->setLinkAttributes(array_merge($menu->getLinkAttributes(), $data['linkAttributes']));
            foreach ($menu->getChildren() as $child) {
                $menuUpdate($child);
            }
        };
        $menuUpdate($menu);
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
}