<?php


namespace EE\TYSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator;

class BasicController extends Controller
{

    /**
     * Creates a form to delete a Story entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * @param $entity
     */
    protected function handleUpload(&$entity)
    {
        if ($entity->getFile()) {
            $uploadsAdapter = $this->container->get('knp_gaufrette.filesystem_map')->get('uploads');
            if ($entity->getBackgroundFilename() !== null) {
                try {
                    $uploadsAdapter->delete($entity->getBackgroundFilename());
                } catch (\RuntimeException $e) {
                    // file didn't exist on server, don't do anything
                };
            }

            $key = sha1(uniqid() . mt_rand(0, 99999)) . '.' . $entity->getFile()->guessExtension();
            $uploadsAdapter->write($key, file_get_contents($entity->getFile()));

            $entity->setBackgroundFilename($key);
        }
    }

    /**
     * @param string $permission
     * @param null $domainObject
     *
     * @return bool
     */
    public function isGranted($permission, $domainObject = null)
    {
        return $this->container->get('security.context')->isGranted($permission, $domainObject);
    }

}
