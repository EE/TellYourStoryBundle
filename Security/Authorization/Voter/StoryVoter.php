<?php

namespace EE\TYSBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use EE\TYSBundle\Entity\Story;

/**
 * Class StoryVoter
 *
 * @package EE\TYSBundle\Security\Authorization\Voter
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class StoryVoter implements VoterInterface
{

    private $container;
    private $supportedAttributes = array(
        'get',
    );

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function supportsAttribute($attribute)
    {

        if (in_array($attribute, $this->supportedAttributes)) {
            return true;
        }

        return false;
    }

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
                case 'get':
                    // @TODO secure not published stories, when it will be implemented
                    return VoterInterface::ACCESS_GRANTED;
                    break;
                default:
                    break;
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
