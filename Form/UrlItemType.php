<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UrlItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class UrlItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url', 'url');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EE\TYSBundle\Entity\UrlItem'
        ));
    }

    public function getName()
    {
        return 'ee_tysbundle_urlitemtype';
    }
}
