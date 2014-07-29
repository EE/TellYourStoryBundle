<?php

namespace EE\TYSBundle\Controller;

use EE\TYSBundle\Entity\StoryRepository;
use EE\TYSBundle\Form\AdminStoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use EE\TYSBundle\Entity\Story;
use EE\TYSBundle\Form\StoryType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Story controller.
 *
 */
class StoryController extends BasicController
{

    /**
     * Lists all Story entities.
     *
     * @param null|integer $by
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($by = null)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var StoryRepository $repository */
        $repository = $em->getRepository('EETYSBundle:Story');

        if ($by) {
            $entities = $repository->findBy(array('created_by' => $by));
        } else {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entities = $repository->findAll();
            } else {
                if ($this->getUser()) {

                    $entities = $repository->getOwnedOrPublishedQuery($this->getUser())->execute();
                } else {
                    $entities = $repository->getPublishedQuery()->execute();
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
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Story();

        if (false === $this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm($this->getFormType(), $entity);
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
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Story entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new Story();

        if (false === $this->isGranted('NEW', $entity)) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm($this->getFormType(), $entity);

        return $this->render('EETYSBundle:Story:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
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

    private function show($identifier, $kind = 'id')
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EETYSBundle:Story')
            ->findOneBy($kind === 'id' ? array("id" => $identifier) : array("slug" => $identifier));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        if (false === $this->isGranted('SHOW', $entity)) {
            throw new AccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('EETYSBundle:Story:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Finds and displays a Story entity.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->show($id);
    }

    /**
     * Finds and displays a Story entity by slug
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBySlugAction($slug)
    {
        return $this->show($slug, 'slug');
    }

    /**
     * @param $identifier
     * @param string $kind
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function preview($identifier, $kind = 'id')
    {
        $em = $this->getDoctrine()->getManager();

        /* @var Story $entity */
        $entity = $em->getRepository('EETYSBundle:Story')
            ->findOneBy($kind === 'id' ? array("id" => $identifier) : array("slug" => $identifier));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $flash = $this
            ->get('translator')
            ->trans($entity->getPublished() ? 'story.published.banner' : 'story.publish_to_share.banner', array(
                '%url%' => $entity->getPublished() ? $this->generateShareUrl($entity)
                    : $this->generateUrl('story_publish', array("id" => $entity->getId()))
            ));
        $this->get('session')->getFlashBag()->add('notice', $flash);

        return $this->render('EETYSBundle:Story:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Finds and displays a Story entity.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction($id)
    {
        return $this->preview($id);
    }

    /**
     * Finds and displays a Story entity.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewBySlugAction($slug)
    {
        return $this->preview($slug, 'slug');
    }


    /**
     * Sets Story::coeditable property
     *
     * @param integer $id
     * @param boolean $isCoeditable
     * @return RedirectResponse
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
     * @param integer $id
     * @return RedirectResponse
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

        $flash = $this->get('translator')->trans('story.published.banner');
        $this->get('session')->getFlashBag()->add('notice', $flash);

        return new RedirectResponse($this->generateUrl('story_show', array('id' => $id)));
    }

    /**
     * Displays a form to edit an existing Story entity.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
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

        $editForm = $this->createForm($this->getFormType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EETYSBundle:Story:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
        $editForm = $this->createForm($this->getFormType(), $entity, array('method' => 'PUT'));
        $editForm->submit($request);

        if ($editForm->isValid()) {

            $this->handleUpload($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_select_type', array('storyId' => $id)));
        }

        return $this->render('EETYSBundle:Story:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Story entity.
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
            $entity = $em->getRepository('EETYSBundle:Story')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Story entity.');
            }

            if (false === $this->isGranted('DELETE', $entity)) {
                throw new AccessDeniedException();
            }

            $em->remove($entity);
            $em->flush();
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            // Admin is supposed to be redirected to story list
            return new RedirectResponse($this->generateUrl('eetys_admin_stories'));
        } else {
            // while user has no story list at all, thus is redirected to dashboard (which is a kind of story list)
            return new RedirectResponse($this->generateUrl('eetys_user_dashboard'));
        }

    }

    /**
     * @param Story $entity
     * @return string
     */
    private function generateShareUrl(Story $entity)
    {
        $link = $this->getRequest()->getSchemeAndHttpHost() .
            $this->generateUrl('story_show_by_slug', array("slug" => $entity->getSlug()));
        $final = array();
        foreach (array(
                     "link" => $link,
                     "app_id" => $this->container->getParameter('oauth_facebook_app_id'),
                     "redirect_uri" => $link
                 ) as $part => $value) {
            $final[] = implode('=', array($part, $value));
        }
        $params = implode('&', $final);
        return 'https://www.facebook.com/dialog/feed?' . $params;
    }

    /**
     * Chooses the right form to be used according to user permissions
     * @return AdminStoryType|StoryType
     */
    private function getFormType()
    {
        return $this->isGranted('ROLE_ADMIN') ?
            new AdminStoryType($this->get('validator')) :
            new StoryType($this->get('validator'));
    }
}
