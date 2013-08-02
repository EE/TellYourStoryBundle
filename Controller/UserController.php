<?php

namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package EE\TYSBundle\Controller
 * @author  Jarek Rencz <jaroslaw@rencz.pl>
 */
class UserController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('EETYSBundle:User:dashboard.html.twig');
    }
}