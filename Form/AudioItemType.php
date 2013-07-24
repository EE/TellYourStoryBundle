<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AudioItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class AudioItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('uploadedFiles', 'file');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EE\TYSBundle\Entity\AudioItem'
        ));
    }

    public function getName()
    {
        return 'ee_tysbundle_audioitemtype';
    }
}
