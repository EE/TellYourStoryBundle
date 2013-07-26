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
            ->add(
                'name',
                null,
                array(
                    'label' => 'tys.form.fileItem.name.label',
                    'help_block' => 'tys.form.fileItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.fileItem.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'tys.form.fileItem.description.label',
                    'help_block' => 'tys.form.fileItem.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.fileItem.description.placeholder',
                    )
                )
            )
            ->add('uploadedFiles', 'file', array(
                    'label' => 'tys.form.fileItem.uploadedFiles.label',
                    'help_block' => 'tys.form.fileItem.uploadedFiles.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.fileItem.uploadedFiles.placeholder',
                    )
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\FileItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_fileitemtype';
    }
}
