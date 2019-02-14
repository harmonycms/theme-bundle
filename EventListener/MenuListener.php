<?php

namespace Harmony\Bundle\ThemeBundle\EventListener;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Harmony\Bundle\MenuBundle\Event\ConfigureMenuEvent;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class MenuListener
 *
 * @package Harmony\Bundle\ThemeBundle\EventListener
 */
class MenuListener
{

    /** @var AbstractKernel|KernelInterface $kernel */
    protected $kernel;

    /**
     * MenuListener constructor.
     *
     * @param KernelInterface|AbstractKernel $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
    }
}