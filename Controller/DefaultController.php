<?php

namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package EE\TYSBundle\Controller
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class DefaultController extends Controller
{
    public function homepageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EETYSBundle:FileItem')->findAll();

        return $this->render('EETYSBundle:Default:homepage.html.twig', array(
            'entities' => $entities
        ));
    }

    public function privacyPolicyAction()
    {
        return $this->render('EETYSBundle:Default:privacypolicy.html.twig');
    }

    public function regulationsAction()
    {
        return $this->render('EETYSBundle:Default:regulations.html.twig');
    }
}
