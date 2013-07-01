<?php

namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homepageAction()
    {
        return $this->render('EETYSBundle:Default:homepage.html.twig');
    }
}
