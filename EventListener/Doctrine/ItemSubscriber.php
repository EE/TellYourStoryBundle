<?php

namespace EE\TYSBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\Common\EventSubscriber,
    EE\TYSBundle\Entity\VideoItem;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ItemSubscriber
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ItemSubscriber implements EventSubscriber
{
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->prePersistAndPreUpdate($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->prePersistAndPreUpdate($args);
    }

    private function prePersistAndPreUpdate(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if ($entity instanceof VideoItem) {
            $embedly = $this->container->get('embedly');

            $oembed = $embedly->oembed($entity->getUrl());

            if ($oembed->type === 'video') {
                $entity->setEmbedCode($oembed->html);
            }
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
