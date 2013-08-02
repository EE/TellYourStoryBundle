<?php

namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('EETYSBundle:User:dashboard.html.twig', array(
            'organizationForm' =>  $this->createOrganizationForm(
                $this->getUser()->getId()
            )->createView()
        ));
    }

    public function setOrganizationAction(Request $request)
    {
        $form = $this->createOrganizationForm($this->getUser()->getId());
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('EETYSBundle:User')->find($this->getUser()->getId());

            if (!$user) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            $formdata = $form->getData();
            $user->setOrganization($formdata['organization']);

            $em->persist($user);
            $em->flush();
        } else {
            die(var_dump($form->getErrorsAsString(9)));

        }

        return $this->redirect($this->generateUrl('eetys_user_dashboard'));

    }


    /**
     * Creates a form to set user organization
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createOrganizationForm()
    {
        return $this->createFormBuilder(null)
            ->add(
                'organization',
                'text',
                array(
                    'label' => 'tys.form.user.organization.set.label',
                    'attr' => array(
                        'placeholder' => 'tys.form.user.organization.set.placeholder',
                    )
                )
            )
            ->getForm();
    }
}