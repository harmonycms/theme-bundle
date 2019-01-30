<?php

namespace Harmony\Bundle\ThemeBundle\EventListener;

use Helis\SettingsManagerBundle\Settings\SettingsRouter;
use Liip\ThemeBundle\ActiveTheme;
use Liip\ThemeBundle\Helper\DeviceDetectionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Listens to the request and changes the active theme based on a cookie.
 *
 * @author Tobias Ebnöther <ebi@liip.ch>
 * @author Pascal Helfenstein <pascal@liip.ch>
 */
class ThemeRequestListener
{

    /**
     * @var ActiveTheme
     */
    protected $activeTheme;

    /**
     * @var DeviceDetectionInterface
     */
    protected $autoDetect;

    /**
     * @var string
     */
    protected $newTheme;

    /** @var SettingsRouter $settingsRouter */
    protected $settingsRouter;

    /**
     * @param ActiveTheme              $activeTheme
     * @param DeviceDetectionInterface $autoDetect If to auto detect the theme based on the user agent
     * @param SettingsRouter           $settingsRouter
     */
    public function __construct(ActiveTheme $activeTheme, SettingsRouter $settingsRouter,
                                DeviceDetectionInterface $autoDetect = null)
    {
        $this->activeTheme    = $activeTheme;
        $this->autoDetect     = $autoDetect;
        $this->settingsRouter = $settingsRouter;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            if ($this->autoDetect) {
                $this->autoDetect->setUserAgent($event->getRequest()->server->get('HTTP_USER_AGENT'));
            }

            $value = $this->settingsRouter->get('theme', null);
            if (!$value && $this->autoDetect instanceof DeviceDetectionInterface) {
                $value = $this->autoDetect->getType();
            }

            if ($value && $value !== $this->activeTheme->getName() &&
                in_array($value, $this->activeTheme->getThemes())) {
                $this->activeTheme->setName($value);
            }
        }
    }
}