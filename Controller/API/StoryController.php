<?php

namespace EE\TYSBundle\Controller\API;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use EE\TYSBundle\Entity\StoryRepository;
use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use EE\TYSBundle\Entity\Story;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Class StoryController
 *
 * @package EE\TYSBundle\Controller\API
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class StoryController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        // @TODO get these from controller itself
        parent::__construct('EETYSBundle', 'Story');

    }

    /**
     * Get collection of resource objects
     *
     * @REST\Route("/stories/by/{user_id}", requirements={"_format"="json|xml"})
     * @REST\View(serializerGroups={"cget"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $user_id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetByUserAction(Request $request, $user_id)
    {
        return $this->getResourceRepository()->findBy(array(
            'createdBy' => $user_id
        ));
    }

    /**
     * Get collection of resource objects
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     * @REST\View(serializerGroups={"cget"})
     *
     * @QueryParam(name="by", requirements=".+", description="Criterion type")
     * @QueryParam(name="id", requirements="\d+", description="Criterion")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction()
    {
        /** @var StoryRepository $storyRepo */
        $storyRepo = $this->getResourceRepository();

        // Resolve query params
        /** @var ParameterBag $params */
        $params = $this->get('request')->query;
        $by = $params->get('by');
        $id = $params->get('id');
        if ($by && $id) {
            // TODO: this is a workaround. Probably waits for 2.5
            // SEE: http://www.doctrine-project.org/jira/browse/DDC-2988
            if ($by === 'storyCollections') {
                if ($this->isGranted('ROLE_ADMIN')) {
                    return $storyRepo->findAllByCollection($id);
                } else {
                    if ($this->getUser()) {
                        return $storyRepo->findOwnedOrPublishedByCollection($id, $this->getUser());
                    } else {
                        return $storyRepo->findPublishedByCollection($id);
                    }
                }
            }
//            return $storyRepo->findBy(array($params->get('by') => $params->get('id')));
        }

        // Collections are always public.
        $collections = $this->getResourceRepository('StoryCollection')->findAll();

        if ($this->isGranted('ROLE_ADMIN')) {
            $stories = $storyRepo->findAllNotInCollections();
        } else {
            if ($this->getUser()) {
                $stories = $storyRepo->findOwnedOrPublishedNotInCollections($this->getUser());
            } else {
                $stories = $storyRepo->findPublishedNotInCollections();
            }
        }

        $combined = new ArrayCollection(array_merge($collections, $stories));

        return $combined->matching(Criteria::create()->orderBy(array("createdAt" => Criteria::DESC)));


    }

    /**
     * Create a new resource
     *
     * @param Request $request
     *
     * @return View view instance
     */
    public function postAction(Request $request)
    {

        $story = new Story();

        // temporary
        $story->setName($request->get('name'));
        $story->setDescription($request->get('description'));
        $story->setAddress($request->get('address'));

        $validator = $this->get('validator');
        $errors = $validator->validate($story);

        if (count($errors) > 0) {
            $data = $errors;
            $statusCode = Codes::HTTP_BAD_REQUEST;

        } else {
            $m = $this->getDoctrine()->getManager();

            $m->persist($story);
            $m->flush();

            $data = $story;
            $statusCode = Codes::HTTP_CREATED;
        }

        return $this->view($data, $statusCode);

    }
}
