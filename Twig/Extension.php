<?php

namespace Harmony\Bundle\ThemeBundle\Twig;

use Sonata\SeoBundle\Seo\SeoPageInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Class Extension
 *
 * @package Harmony\Bundle\ThemeBundle\Twig
 */
class Extension extends AbstractExtension implements GlobalsInterface
{

    /** @var SeoPageInterface|null $page */
    protected $page;

    /**
     * Extension constructor.
     *
     * @param SeoPageInterface|null $page
     */
    public function __construct(?SeoPageInterface $page = null)
    {
        $this->page = $page;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals(): array
    {
        return [
            'harmony' => [
                'site_name' => $this->page ? $this->page->getTitle() : ''
            ]
        ];
    }
}