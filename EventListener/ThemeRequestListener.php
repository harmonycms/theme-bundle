<?php

namespace Harmony\Bundle\ThemeBundle\EventListener;

use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Helis\SettingsManagerBundle\Settings\SettingsRouter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ThemeRequestListener
 *
 * @package Harmony\Bundle\ThemeBundle\EventListener
 */
class ThemeRequestListener
{

    /**
     * @var string
     */
    protected $newTheme;

    /** @var SettingsRouter $settingsRouter */
    protected $settingsRouter;

    /** @var KernelInterface $kernel */
    protected $kernel;

    /** @var TranslatorInterface|DataCollectorTranslator|Translator $translator */
    protected $translator;

    /**
     * @param KernelInterface|AbstractKernel              $kernel
     * @param SettingsRouter                              $settingsRouter
     * @param TranslatorInterface|DataCollectorTranslator $translator
     */
    public function __construct(KernelInterface $kernel, SettingsRouter $settingsRouter,
                                TranslatorInterface $translator)
    {
        $this->kernel         = $kernel;
        $this->settingsRouter = $settingsRouter;
        $this->translator     = $translator;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $value = $this->settingsRouter->get('theme');

            if ((null !== $theme = $this->kernel->getThemes()[$value] ?? null) &&
                $this->translator instanceof DataCollectorTranslator) {

                $finder = (new Finder())->files();
                if (\is_dir($transPath = $theme->getPath() . '/translations')) {
                    $finder->in($transPath);
                }
                if (null !== $theme->getParent() &&
                    \is_dir($parentTransPath = $theme->getParent()->getPath() . '/translations')) {
                    $finder->in($parentTransPath);
                }

                try {
                    /** @var \SplFileInfo $file */
                    foreach ($finder as $file) {
                        list($domain, $locale) = explode('.', $file->getBasename(), 3);
                        switch ($file->getExtension()) {
                            case 'php':
                                $this->translator->addResource('php', (string)$file, $locale, $domain);
                                break;
                            case 'xlf':
                                $this->translator->addResource('xlf', (string)$file, $locale, $domain);
                                break;
                            case 'yaml':
                                $this->translator->addResource('yaml', (string)$file, $locale, $domain);
                                break;
                        }
                    }
                }
                catch (\LogicException $e) {
                }
            }
        }
    }
}