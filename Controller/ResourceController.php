<?php

namespace EE\TYSBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as REST,
    FOS\RestBundle\Controller\FOSRestController,
    FOS\Rest\Util\Codes;

use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * Class ResourceController
 *
 * @package EE\TYSBundle\Controller
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
     * @REST\Route(requirements={"_format"="html|json|xml","id"="\d+"} )
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction()
    {
        return $this->getResourceRepository()->findAll();
    }

    /**
     *
     * @param integer $id
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction($id)
    {
        // @TODO
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

}
