<?php

namespace EE\TYSBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use EE\TYSBundle\Entity\Item;

/**
 * Class ItemVoter
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ItemVoter implements VoterInterface
{

    private $container;
    private $supportedAttributes = array(
        'NEW',
        'EDIT',
        'SHOW',
        'DELETE'
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
        if ($class instanceof Item) {
            return true;
        }

        return false;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {

        if (!$this->supportsClass($object)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            switch ($attribute) {
                case 'NEW':

                    return $this->newAccess($object, $user);
                    break;
                case 'EDIT':

                    return $this->editAccess($object, $user);
                    break;
                case 'SHOW':

                    return $this->showAccess($object);
                    break;
                case 'DELETE':

                    return $this->showAccess($object, $user);
                    break;
                default:
                    break;
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }

    private function newAccess(Item $object, UserInterface $user)
    {
        if (!$object->getStory()) {
            throw new \RuntimeException('Missing reference to Story entity. Remember to $item->setStory($story) before calling is_granted NEW ');
        }

        if (
            ($user === $object->getStory()->getCreatedBy())
            ||
            (true === $object->getStory()->getCoeditable())
        ) {
            throw new \RuntimeException('Missing reference to Story entity. Remember to $item->setStory($story) before calling is_granted NEW ');
        }

        return VoterInterface::ACCESS_GRANTED;
    }

    private function editAccess(Item $object, UserInterface $user)
    {
        if ($user instanceof UserInterface && $user === $object->getCreatedBy() && null !== $object->getStory()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    private function showAccess(Item $object)
    {

        return VoterInterface::ACCESS_GRANTED;
    }

    private function deleteAccess(Item $object, UserInterface $user)
    {
        if ($user instanceof UserInterface && $user === $object->getCreatedBy() && null !== $object->getStory()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
