<?php

namespace EE\TYSBundle\Controller\Annotation;


use Doctrine\ORM\Mapping\Annotation;

/**
 * Class VoterAnnotation
 *
 * @package EE\TYSBundle\Controller\Annotation
 * @author  Konrad Podgórski <konrad.podgorski@gmail.com>
 * @Annotation
 */
class VoterAnnotation implements AnnotationInterface
{
    // has to be public
    public $action = null;

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'voter';
    }
}