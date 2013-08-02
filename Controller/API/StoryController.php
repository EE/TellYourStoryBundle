<?php

namespace EE\TYSBundle\Controller\API;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\View\View;
use EE\TYSBundle\Entity\Story;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetByUserAction(Request $request, $user_id)
    {
        return $this->getResourceRepository()->findBy(array(
            'createdBy' => $user_id
        ));
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
