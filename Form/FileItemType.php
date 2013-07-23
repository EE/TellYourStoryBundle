<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FileItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class FileItemType extends AbstractType
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
            'data_class' => 'EE\TYSBundle\Entity\FileItem'
        ));
    }

    public function getName()
    {
        return 'ee_tysbundle_fileitemtype';
    }
}
