<?php

namespace EE\TYSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EE\TYSBundle\Entity\Story;

class LoadStoryData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $story = new Story();
        $story->setName('First Story');
        $story->setDescription('First story description');
        $story->setAddress('Poland, Warsaw');

        $manager->persist($story);
        $manager->flush();
    }
}