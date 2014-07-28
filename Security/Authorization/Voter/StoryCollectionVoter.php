<?php

namespace EE\TYSBundle\Security\Authorization\Voter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use EE\TYSBundle\Entity\StoryCollection;

/**
 * Class StoryCollectionVoter
 *
 * @author Jarek Rencz <jarek.rencz@laboratorium.ee>
 */
class StoryCollectionVoter implements VoterInterface
{

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var array
     */
    private $supportedAttributes = array(
        'NEW',
        'EDIT',
        'SHOW',
        'DELETE',
    );

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        if (in_array($attribute, $this->supportedAttributes)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        if ($class instanceof StoryCollection) {
            return true;
        }

        return false;
    }

    /**
     * @param TokenInterface $token
     * @param object $object
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass($object)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            // For now only admin can perform actions other than show.
            switch ($attribute) {
                case 'SHOW':
                    return $this->showAccess();
                    break;
                default:
                    break;
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * Everyone can see collections.
     *
     * @return int
     */
    private function showAccess()
    {
        return VoterInterface::ACCESS_GRANTED;
    }

}
