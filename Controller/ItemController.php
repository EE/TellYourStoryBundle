<?php

namespace EE\TYSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EE\TYSBundle\Entity\FileItem;
use EE\TYSBundle\Form\FileItemType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Item controller.
 *
 */
class ItemController extends Controller
{
    /**
     * Lists all Item entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EETYSBundle:Item')->findAll();

        return $this->render(
            'EETYSBundle:Item:index.html.twig',
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     *
     * @param integer $storyId
     * @param string  $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectTypeAction($storyId)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository('EETYSBundle:Story')->find($storyId);

        if (!$story) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        return $this->render(
            'EETYSBundle:Item:select_type.html.twig',
            array(
                'story' => $story
            )
        );
    }

    /**
     * Creates a new Item entity.
     *
     * @param Request $request
     * @param integer $storyId
     * @param string  $type
     *
     * @throws AccessDeniedException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, $storyId, $type)
    {
        $form = $this->createForm(sprintf('ee_tysbundle_%sitemtype', $type));
        $form->submit($request);
        $item = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository('EETYSBundle:Story')->find($storyId);

        if (!$story) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $item->setStory($story);

        if (false === $this->isGranted('NEW', $item)) {
            throw new AccessDeniedException();
        }

        if ($form->isValid()) {

            $data = $request->files->get($form->getName());

            $uploadsAdapted = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');

            if (isset(
                $data['uploadedFiles']) &&
                !(count($data['uploadedFiles']) === 1 && is_null($data['uploadedFiles'][0]))
            ) {
                foreach ((array) $data['uploadedFiles'] as $uploadedFile) {
                    // Symfony\Component\HttpFoundation\File\UploadedFile

                    $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $uploadedFile->guessExtension();

                    $uploadsAdapted->write($key, file_get_contents($uploadedFile->getPathName()));
                    $item->addFile($key);
                }
            }
            $item->setStory($story);

            $item->setCreatedBy($this->getUser());

            $em->persist($item);
            $em->flush();

            return $this->redirect($this->generateUrl('item_select_type', array('storyId' => $item->getStory()->getId())));
        }

        return $this->render(
            'EETYSBundle:Item:new.html.twig',
            array(
                'story' => $story,
                'entity' => $item,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new Item entity.
     *
     * @param integer $storyId
     * @param string  $type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($storyId, $type)
    {
        $form = $this->createForm(sprintf('ee_tysbundle_%sitemtype', $type));
        $form->submit($this->getRequest());
        $entity = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $story = $em->getRepository('EETYSBundle:Story')->find($storyId);

        $form = $this->createForm(sprintf('ee_tysbundle_%sitemtype', $type));

        if (!$story) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $entity->setStory($story);

        if (false === $this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }


        return $this->render(
            'EETYSBundle:Item:new.html.twig',
            array(
                'story' => $story,
                'entity' => $entity,
                'form' => $form->createView()
            )
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Item')->find($id);

        if (false === $this->isGranted('SHOW', $entity)) {
            throw new AccessDeniedException();
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'EETYSBundle:Item:show.html.twig',
            array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        if (false === $this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(sprintf('ee_tysbundle_%sitemtype', $entity->getType()), $entity);
        $deleteForm = $this->createDeleteForm($id);


        $story = $em->getRepository('EETYSBundle:Story')->findOneBy(
            array(
                'id' => $entity->getStory()->getId()
            )
        );

        if (!$story) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        return $this->render(
            'EETYSBundle:Item:select_type.html.twig',
            array(
                'story' => $story,
                'edited_item' => $entity,
                'edited_item_edit_form' => $editForm->createView(),
                'edited_item_delete_form' => $deleteForm->createView(),
            )
        );

    }

    /**
     * Edits an existing Item entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('EETYSBundle:Item')->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        if (false === $this->isGranted('EDIT', $item)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(sprintf('ee_tysbundle_%sitemtype', $item->getType()), $item);
        $editForm->submit($request);

        if ($editForm->isValid()) {

            if ($data = $request->files->get($editForm->getName())) {

                $uploadsAdapted = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');

                if (isset(
                    $data['uploadedFiles']) &&
                    !(count($data['uploadedFiles']) === 1 && is_null($data['uploadedFiles'][0]))
                ) {
                    foreach ($data['uploadedFiles'] as $uploadedFile) {
                        // Symfony\Component\HttpFoundation\File\UploadedFile


                        $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $uploadedFile->guessExtension();

                        $uploadsAdapted->write($key, file_get_contents($uploadedFile->getPathName()));
                        $item->addFile($key);
                    }
                }

            }

            $em->persist($item);
            $em->flush();

            return $this->redirect($this->generateUrl('item_select_type', array('storyId' => $item->getStory()->getId())));
        }

        return $this->render(
            'EETYSBundle:Item:edit.html.twig',
            array(
                'entity' => $item,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Item entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EETYSBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            if (false === $this->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException();
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('item'));
    }

    /**
     * Creates a form to delete a Item entity by id.
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
