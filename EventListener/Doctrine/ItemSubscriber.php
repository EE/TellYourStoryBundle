<?php

namespace EE\TYSBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\Common\EventSubscriber,
    EE\TYSBundle\Entity\VideoItem;

/**
 * Class ItemSubscriber
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ItemSubscriber implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof VideoItem) {
            // @TODO use embed.ly api here to get embedCode
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }
}
