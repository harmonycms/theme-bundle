<?php

namespace Harmony\Bundle\ThemeBundle\EventListener;

use Harmony\Bundle\ThemeBundle\Model\Theme;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

/**
 * Class SerializationSubscriber
 *
 * @package Harmony\Bundle\ThemeBundle\EventListener
 */
class SerializationSubscriber implements EventSubscriberInterface
{

    /**
     * Returns the events to which this class has subscribed.
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' =>
     *         'json'), array(...),
     *     )
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event'  => Events::PRE_DESERIALIZE,
                'method' => 'onPreDeserialize',
                'class'  => Theme::class,
                'format' => 'json'
            ],
            [
                'event'  => Events::POST_DESERIALIZE,
                'method' => 'onPostThemeDeserialize',
                'class'  => Theme::class,
                'format' => 'json'
            ]
        ];
    }

    /**
     * @param PreDeserializeEvent $event
     */
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        if (isset($data['extra'])) {
            $data['extra'] = array_intersect_key($data['extra'], array_flip(['harmony-theme']));
            if (isset($data['extra']['harmony-theme'])) {
                $data['extra'] = $data['extra']['harmony-theme'];
            }
        }
        $event->setData($data);
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostThemeDeserialize(ObjectEvent $event)
    {
        /** @var Theme $theme */
        $theme = $event->getObject();
        $theme->setPreview('/themes/' . strtr($theme->getPreview(), ['%current_theme%' => $theme->getDir()]));
        if (null !== $extra = $theme->getExtra()) {
            $extra->setPreview('/themes/' . strtr($extra->getPreview(), ['%current_theme%' => $theme->getDir()]));
        }
    }
}