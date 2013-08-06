<?php

namespace EE\TYSBundle\Controller\API;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as REST,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\RestBundle\Routing\ClassResourceInterface;

use EE\TYSBundle\Controller\Annotation\VoterAnnotation as Voter;


/**
 * Class ResourceController
 *
 * @package EE\TYSBundle\Controller\API
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ResourceController extends FOSRestController implements ClassResourceInterface
{
    protected $bundlePrefix;
    protected $resourceName;

    /**
     * @param string $bundlePrefix
     * @param string $resourceName
     */
    public function __construct($bundlePrefix, $resourceName)
    {
        $this->bundlePrefix = $bundlePrefix;
        $this->resourceName = $resourceName;
    }

    /**
     * Get single resource object
     *
     * @param integer $id
     *
     * @REST\Route(requirements={"_format"="json|xml","id"="\d+"} )
     * @REST\View(serializerGroups={"get"})
     *
     * @Voter(action="get")
     *
     * @throws NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($id)
    {
        $data = $this->getResourceRepository()->find($id);

        if ($data === null) {
            throw new NotFoundHttpException(sprintf('Requested %s does not exist', $this->getResourceName()));
        }

        return $data;
    }

    /**
     * Get collection of resource objects
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     * @REST\View(serializerGroups={"cget"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction()
    {
        return $this->getResourceRepository()->findAll();
    }

    // helpers

    public function getResourceRepository($resourceName = null)
    {
        if ($resourceName === null) {
            $resourceName = $this->resourceName;
        }

        return $this->getDoctrine()->getManager()->getRepository(sprintf('%s:%s', $this->bundlePrefix, $resourceName));
    }

    public function getResourceName()
    {
        return $this->resourceName;
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
