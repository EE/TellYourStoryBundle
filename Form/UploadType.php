<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class UploadType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'upload';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'required' => true,
            )
        );
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
        if ($form->getParent()->getData()->getId()) {
            // this is not new, so make it not required
            $view->vars['required'] = false;
        }
    }
}