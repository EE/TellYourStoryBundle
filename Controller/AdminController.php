<?php

namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package EE\TYSBundle\Controller
 * @author  Jarek Rencz <jaroslaw@rencz.pl>
 */
class AdminController extends Controller
{
    public function homepageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stories = $em->getRepository('EETYSBundle:Story')->findAll();

        return $this->render('EETYSBundle:Admin:homepage.html.twig', array(
            'stories' => $stories
        ));
    }

    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('EETYSBundle:User')->findAll();

        return $this->render('EETYSBundle:Admin:users.html.twig', array(
            'entities' => $users
        ));
    }
}
