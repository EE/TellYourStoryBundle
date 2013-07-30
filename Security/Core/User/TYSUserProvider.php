<?php
namespace EE\TYSBundle\Security\Core\User;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use EE\LightUserBundle\Security\Core\User\UserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use EE\TYSBundle\Entity\User;


class TYSUserProvider extends UserProvider implements OAuthAwareUserProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $username = $response->getUsername();
        $service = $response->getResourceOwner()->getName();

        $serviceUsername = $service . '_' . $username;

        try {
            $user = $this->loadUserByUsername($serviceUsername);
        } catch (UsernameNotFoundException $e) {

            $user = $this->userManager->createUser();

            $setter = 'set' . ucfirst($service);
            $setterId = $setter . 'Id';
            $setterToken = $setter . 'AccessToken';

            $user->setUsername($serviceUsername);

            $user->$setterId($username);
            $user->$setterToken($response->getAccessToken());

            $this->userManager->updateUser($user);

        }

        return $user;
    }
}