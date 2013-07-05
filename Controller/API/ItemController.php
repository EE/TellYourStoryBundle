<?php

namespace EE\TYSBundle\Controller\API;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Class ItemController
 *
 * @package EE\TYSBundle\Controller\API
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ItemController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        // @TODO get these from controller itself
        parent::__construct('EETYSBundle', 'Item');

    }

}
