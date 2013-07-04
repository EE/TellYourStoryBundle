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
        return $this->render('EETYSBundle:Default:homepage.html.twig');
    }
}
