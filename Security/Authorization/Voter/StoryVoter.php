<?php

namespace EE\TYSBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use EE\TYSBundle\Entity\Story;

/**
 * Class StoryVoter
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class StoryVoter implements VoterInterface
{

    private $container;
    private $supportedAttributes = array(
        'NEW',
        'EDIT',
        'SHOW',
        'DELETE',
        'PUBLISH'
    );

    public function __construct($container)
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
        if ($class instanceof Story) {
            return true;
        }

        return false;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {

        if (!$this->supportsClass($object)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            switch ($attribute) {
                case 'NEW':

                    return $this->newAccess($object);
                    break;
                case 'EDIT':

                    return $this->editAccess($object);
                    break;
                case 'SHOW':

                    return $this->showAccess($object);
                    break;
                case 'DELETE':

                    return $this->deleteAccess($object);
                    break;
                case 'PUBLISH':

                    return $this->publishAccess($object);
                    break;
                default:
                    break;
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }

    private function newAccess(Story $object)
    {

        return VoterInterface::ACCESS_GRANTED;
    }

    private function editAccess(Story $object)
    {

        return VoterInterface::ACCESS_GRANTED;
    }

    private function showAccess(Story $object)
    {

        return VoterInterface::ACCESS_GRANTED;
    }

    private function deleteAccess(Story $object)
    {

        return VoterInterface::ACCESS_GRANTED;
    }

    private function publishAccess(Story $object){

        return VoterInterface::ACCESS_GRANTED;
    }
}
