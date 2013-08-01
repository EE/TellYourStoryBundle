<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ImageType extends AbstractType
{
    public function getName()
    {
        return 'image';
    }

    public function getParent()
    {
        return 'file';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required' => true,
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getParent()->getData()->getId()) {
            // this is not new, so make it not required
            $view->vars['required'] = false;
        }
    }
}