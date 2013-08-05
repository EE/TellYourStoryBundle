<?php

namespace EE\TYSBundle\Controller;

use EE\TYSBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DefaultController
 *
 * @package EE\TYSBundle\Controller
 * @author  Jarek Rencz <jaroslaw@rencz.pl>
 */
class AdminController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('EETYSBundle:Admin:dashboard.html.twig');
    }

    public function storiesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $stories = $em->getRepository('EETYSBundle:Story')->findAll();

        return $this->render('EETYSBundle:Admin:stories.html.twig', array(
            'stories' => $stories
        ));
    }

    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('EETYSBundle:User')->findAll();

        return $this->render('EETYSBundle:Admin:users.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EETYSBundle:User')->find($id);

        return $this->render('EETYSBundle:User:dashboard.html.twig', array(
            'user' => $user,
            'ban_form' => $this->createBanForm($user)->createView()
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userBanAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('EETYSBundle:User')->find($id);

        $user->setActive(!$user->getActive());
        $em->persist($user);
        $em->flush();

        return new RedirectResponse($this->getRequest()->headers->get("referer"));

    }


    /**
     * @param string    $permission
     * @param null      $domainObject
     *
     * @return bool
     */
    public function isGranted($permission, $domainObject = null)
    {
        return $this->container->get('security.context')->isGranted($permission, $domainObject);
    }


    /**
     * Creates a form to ban a User
     *
     * @param User $user user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createBanForm(User $user)
    {
        return $this->createFormBuilder(array('id' => $user->getId()))
            ->add('id', 'hidden')
            ->getForm();
    }
}
