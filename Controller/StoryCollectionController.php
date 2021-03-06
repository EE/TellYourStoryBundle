<?php


namespace EE\TYSBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use EE\TYSBundle\Entity\StoryCollection;
use EE\TYSBundle\Form\StoryCollectionType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CollectionController
 * @package EE\TYSBundle\Controller
 */
class StoryCollectionController extends BasicController
{
    /**
     * Lists all Story entities.
     *
     */
    public function indexAction($by = null)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('EETYSBundle:StoryCollection');

        if ($by) {
            $entities = $repository->findBy(array('created_by' => $by));
        } else {
            $entities = $repository->findAll();
        }

        return $this->render('EETYSBundle:StoryCollection:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @param {int|string} $identifier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function show($identifier, $kind = 'id')
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var StoryCollection $entity
         */
        $entity = $em->getRepository('EETYSBundle:StoryCollection')
            ->findOneBy($kind === 'id' ? array("id" => $identifier) : array("slug" => $identifier));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story Collection entity.');
        }

        if (false === $this->isGranted('SHOW', $entity)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('EETYSBundle:StoryCollection:show.html.twig', array(
            'entity' => $entity,
            'stories' => $entity->getStories()->toArray(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Finds and displays a Story Collection entity by ID
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->show($id);
    }

    /**
     * Finds and displays a Story Collection entity by slug
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBySlugAction($slug)
    {
        return $this->show($slug, 'slug');
    }

    /**
     * Displays a form to create a new Story entity.
     */
    public function newAction()
    {
        $entity = new StoryCollection();

        if (!$this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm($this->get('ee_tys.form.type.story_collection'), $entity);

        return $this->render('EETYSBundle:StoryCollection:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new StoryCollection entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new StoryCollection();

        if (!$this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm($this->get('ee_tys.form.type.story_collection'), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->handleUpload($entity);
            // Note: $this->getUser() is instance of Symfony\Component\Security\Core\User\User.
            // Only \EE\TYSBundle\Entity\User (a.k.a. database user) makes sense here.
            // THis line may be uncommented if collections are to be created by non-admin users.
            // $entity->setCreatedBy($this->getUser());

            $em->persist($entity);
            $em->flush();

            // After collection gets created go back to the list. The only list is the admin list.
            return new RedirectResponse($this->generateUrl('eetys_admin_collections'));
        }

        return $this->render('EETYSBundle:StoryCollection:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Edits an existing StoryCollection entity.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var StoryCollection $storyCollection */
        $storyCollection = $em->getRepository('EETYSBundle:StoryCollection')->find($id);

        if (!$storyCollection) {
            throw $this->createNotFoundException('Unable to find Story Collection entity.');
        }

        if (false === $this->isGranted('EDIT', $storyCollection)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(
            $this->get('ee_tys.form.type.story_collection'),
            $storyCollection,
            array('method' => 'PUT')
        );
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $this->handleUpload($storyCollection);

            $em->persist($storyCollection);
            $em->flush();

            // After collection gets created go back to the list. The only list is the admin list.
            return $this->redirect($this->generateUrl('eetys_admin_collections'));
        }

        return $this->render('EETYSBundle:StoryCollection:edit.html.twig', array(
            'entity' => $storyCollection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Story entity.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:StoryCollection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm($this->get('ee_tys.form.type.story_collection'), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EETYSBundle:StoryCollection:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Story collection entity.
     *
     * @param Request $request
     * @param integer $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EETYSBundle:StoryCollection')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Story collection entity.');
            }

            if (false === $this->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException();
            }

            $em->remove($entity);
            $em->flush();
        }

        return new RedirectResponse($this->generateUrl('eetys_admin_collections'));
    }
}
