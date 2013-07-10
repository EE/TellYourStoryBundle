<?php

namespace EE\TYSBundle\EventListener;

use EE\TYSBundle\Controller\Annotation\VoterAnnotation;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

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
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        $annotationReader = $this->container->get('annotation_reader');

        $annotation = $annotationReader->getMethodAnnotation(
            $method,
            'EE\TYSBundle\Controller\Annotation\VoterAnnotation'
        );

        if (!$annotation instanceof VoterAnnotation) {
            return;
        }

        $object = $this->container->get('doctrine')->getManager()->find(
            // temporary
            'EETYSBundle:Story',
            $event->getRequest()->get('id')
        );

        if (false === $this->isGranted($annotation->getAction(), $object)) {
            throw new AccessDeniedHttpException();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', -128)
        );
    }


    public function getSecurityContext()
    {
        return $this->container->get('security.context');
    }

    public function isGranted($permission, $domainObject = null)
    {
        return $this->getSecurityContext()->isGranted($permission, $domainObject);
    }

}