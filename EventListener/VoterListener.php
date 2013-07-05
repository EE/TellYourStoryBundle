<?php

namespace EE\TYSBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EE\TYSBundle\Controller\Annotation\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Class VoterListener
 *
 * @package EE\TYSBundle\EventListener
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class VoterListener implements EventSubscriberInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if ($configuration = $request->attributes->get('_voter')) {
            if (!$configuration instanceof Voter) {
                return;
            }
        }
        throw new AccessDeniedException();
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -128)
        );
    }



    public function getSecurityContext(){
        return $this->container->get('security.context');
    }

    public function isGranted($permission, $domainObject = null){
        return $this->getSecurityContext()->isGranted($permission, $domainObject);
    }

}