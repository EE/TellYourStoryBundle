<?php

namespace EE\TYSBundle\Handler;

use EE\TYSBundle\Entity\Filename;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class FilenameHandler implements SubscribingHandlerInterface
{

    function __construct(CacheManager $manager)
    {
        $this->manager = $manager;
    }


    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Filename',
                'method' => 'serializeFilenameToJson',
            ),
        );
    }

    public function serializeFilenameToJson(VisitorInterface $visitor, Filename $filename, array $type)
    {
        return $this->manager->getBrowserPath($filename, 'tile', true);
    }
}
