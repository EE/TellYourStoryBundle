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
            ->add(
                'name',
                null,
                array(
                    'label' => 'tys.form.audioItem.name.label',
                    'help_block' => 'tys.form.audioItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.audioItem.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'tys.form.audioItem.description.label',
                    'help_block' => 'tys.form.audioItem.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.audioItem.description.placeholder',
                    )
                )
            )
            ->add('uploadedFiles', 'file', array(
                    'label' => 'tys.form.audioItem.uploadedFiles.label',
                    'help_block' => 'tys.form.audioItem.uploadedFiles.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.audioItem.uploadedFiles.placeholder',
                    )
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\AudioItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_audioitemtype';
    }
}
