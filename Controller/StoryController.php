<?php

namespace EE\TYSBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EE\TYSBundle\Entity\Story;
use EE\TYSBundle\Form\StoryType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Story controller.
 *
 */
class StoryController extends Controller
{

    /**
     * Lists all Story entities.
     *
     */
    public function indexAction($by = null)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('EETYSBundle:Story');

        if ($by) {
            $entities = $repository->findBy(array('created_by' => $by));
        } else {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entities = $repository->findAll();
            } else {
                if ($this->getUser()) {
                    $entities = $this->getResourceRepository()
                        ->getPublishedOrOwnedQuery($this->getUser()->getId())
                        ->execute();
                } else {
                    $entities = $this->getResourceRepository()->getPublishedQuery()->execute();
                }
            }
        }

        return $this->render('EETYSBundle:Story:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Story entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Story();

        if (false === $this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new StoryType($this->get('validator')), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->handleUpload($entity);
            $entity->setCreatedBy($this->getUser());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_select_type', array('storyId' => $entity->getId())));
        }

        return $this->render('EETYSBundle:Story:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Story entity.
     */
    public function newAction()
    {
        $entity = new Story();

        if (false === $this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form   = $this->createForm(new StoryType($this->get('validator')), $entity);

        return $this->render('EETYSBundle:Story:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     *
     */
    public function addItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $story = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$story) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        return $this->render('EETYSBundle:Story:add_item.html.twig', array(
                'story' => $story
            ));
    }

    /**
     * Finds and displays a Story entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('SHOW', $entity)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EETYSBundle:Story:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
            ));
    }


    /**
     * Finds and displays a Story entity.
     *
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        return $this->render('EETYSBundle:Story:show.html.twig', array(
            'entity'      => $entity,
        ));
    }


    /**
     * Sets Story::coeditable property
     *
     */
    public function setCoeditabilityAction($id, $isCoeditable)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $entity->setCoeditable($isCoeditable);

        $em->persist($entity);
        $em->flush();

        return new RedirectResponse($this->getRequest()->headers->get("referer"));
    }

    /**
     * Sets Story::published property
     *
     */
    public function publishAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('PUBLISH', $entity)) {
            throw new AccessDeniedException();
        }

        $entity->setPublished(1);

        $em->persist($entity);
        $em->flush();

        $flash = $this->get('translator')->trans('story.publish.banner');
        $this->get('session')->getFlashBag()->add('notice', $flash);

        return new RedirectResponse($this->generateUrl('story_show', array('id' => $id)));
    }

    /**
     * Displays a form to edit an existing Story entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new StoryType($this->get('validator')), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EETYSBundle:Story:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Story entity.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StoryType($this->get('validator')), $entity, array('method'=>'PUT'));
        $editForm->submit($request);

        if ($editForm->isValid()) {

            $this->handleUpload($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_select_type', array('storyId' => $id)));
        }

        return $this->render('EETYSBundle:Story:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Story entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EETYSBundle:Story')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Story entity.');
            }

            if (false === $this->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException();
            }

            $em->remove($entity);
            $em->flush();
        } else {
            die(var_dump($form->getErrorsAsString()));
        }
        return new RedirectResponse($this->getRequest()->headers->get("referer"));

    }

    /**
     * Creates a form to delete a Story entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * @param $entity
     */
    private function handleUpload(&$entity)
    {
        if ($entity->getFile()) {
            $uploadsAdapter = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');
            if ($entity->getBackgroundFilename() !== null){
                try {
                    $uploadsAdapter->delete($entity->getBackgroundFilename());
                } catch (\RuntimeException $e) {
                    // file didn't exist on server, don't do anything
                };
            }

            $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $entity->getFile()->guessExtension();
            $uploadsAdapter->write($key, file_get_contents($entity->getFile()));

            $entity->setBackgroundFilename($key);
        }
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
}
