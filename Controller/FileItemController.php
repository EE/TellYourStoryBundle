<?php

namespace EE\TYSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EE\TYSBundle\Entity\FileItem;
use EE\TYSBundle\Form\FileItemType;

/**
 * FileItem controller.
 *
 */
class FileItemController extends Controller
{
    /**
     * Lists all FileItem entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EETYSBundle:FileItem')->findAll();

        return $this->render('EETYSBundle:FileItem:index.html.twig', array(
                'entities' => $entities,
            ));
    }

    /**
     * Creates a new FileItem entity.
     */
    public function createAction(Request $request)
    {
        $entity = new FileItem();
        $form = $this->createForm(new FileItemType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $request->files->get($form->getName());

            $FileItemAdapted = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');

            foreach ($data['uploadedFiles'] as $uploadedFile) {
                // Symfony\Component\HttpFoundation\File\UploadedFile

                $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $uploadedFile->guessExtension();

                $FileItemAdapted->write($key, file_get_contents($uploadedFile->getPathName()));
                $entity->addFile($key);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fileitem_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'EETYSBundle:FileItem:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new FileItem entity.
     *
     */
    public function newAction()
    {
        $entity = new FileItem();
        $form = $this->createForm(new FileItemType(), $entity);

        return $this->render(
            'EETYSBundle:FileItem:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Finds and displays a FileItem entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:FileItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'EETYSBundle:FileItem:show.html.twig',
            array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing FileItem entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:FileItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileItem entity.');
        }

        $editForm = $this->createForm(new FileItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'EETYSBundle:FileItem:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Edits an existing FileItem entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:FileItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FileItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FileItemType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fileitem_edit', array('id' => $id)));
        }

        return $this->render(
            'EETYSBundle:FileItem:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a FileItem entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EETYSBundle:FileItem')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FileItem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('fileitem'));
    }

    /**
     * Creates a form to delete a FileItem entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
