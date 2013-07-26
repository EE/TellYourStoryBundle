<?php

namespace EE\TYSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EE\TYSBundle\Entity\Story;
use EE\TYSBundle\Form\StoryType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EETYSBundle:Story')->findAll();

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
        $form = $this->createForm(new StoryType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->handleUpload($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('story_show', array('id' => $entity->getId())));
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
        $form   = $this->createForm(new StoryType(), $entity);

        return $this->render('EETYSBundle:Story:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EETYSBundle:Story:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()
            ));
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

        $editForm = $this->createForm(new StoryType(), $entity);
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

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StoryType(), $entity, array('method'=>'PUT'));
        $editForm->submit($request);

        if ($editForm->isValid()) {

            $this->handleUpload($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('story_edit', array('id' => $id)));
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

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('story'));
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
        if ($entity->file) {
            $uploadsAdapter = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');
            try {
                $uploadsAdapter->delete($entity->getBackgroundFilename());
            } catch (\RuntimeException $e) {
                // file didn't exist on server, don't do anything
            };

            $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $entity->file->guessExtension();
            $uploadsAdapter->write($key, file_get_contents($entity->file));

            $entity->setBackgroundFilename($key);
        }
    }
}
