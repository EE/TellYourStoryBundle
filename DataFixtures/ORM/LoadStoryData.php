<?php

namespace EE\TYSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EE\TYSBundle\Entity\Story;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadStoryData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $path = '@EETYSBundle/Resources/data/images/';
        $files = array(
            'first' => 'image1.jpeg',
            'second' => 'image2.jpeg',
            'third' => 'image3.jpeg',
            'fourth' => 'image4.jpeg',
            'fifth' => 'image5.jpeg',
            'sixth' => 'image6.jpeg',
            'seventh' => 'image7.jpeg',
            'eighth' => 'image8.jpeg',
            'ninth' => 'image9.jpeg',
            'tenth' => 'image10.jpeg',
        );
        $filehandles = array();

        foreach ($files as $index => $file) {
            $filehandles[$index] = file_get_contents(
                $this->container->get('kernel')->locateResource($path . $file)
            );
        }
        $FileItemAdapted = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');

        $userManager = $this->container->get('ee_light_user.user_manager');

        $user = $userManager->createUser();

        $user->setOrganization('Laboratorium EE');
        $user->setUsername('johnsmith');
        $user->setFacebookRealName('John Smith');

        $user->setActive(false);

        $userManager->updateUser($user);

        foreach ($filehandles as $index => $handle) {

            $s = new Story();
            $s->setName(ucfirst($index) .  ' Story');
            $s->setDescription(ucfirst($index) . ' story description');
            $s->setAddress('Poland, Warsaw');
            $s->setTagline(ucfirst($index));
            $key = sha1(uniqid() . mt_rand(0, 99999)) . '.jpeg';
            $FileItemAdapted->write($key, $handle);
            $s->setBackgroundFilename($key);
            $s->setCreatedBy($user);

            $manager->persist($s);
        }
        
        $manager->flush();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}