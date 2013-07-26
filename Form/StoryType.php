<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class StoryType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'tys.form.story.name.label',
                    'help_block' => 'tys.form.story.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'tys.form.story.description.label',
                    'help_block' => 'tys.form.story.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story.description.placeholder',
                    )
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'tys.form.story.address.label',
                    'help_block' => 'tys.form.story.address.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story.address.placeholder',
                    )
                )
            )
            ->add(
                'file',
                null,
                array(
                    'label' => 'tys.form.story.file.label',
                    'help_block' => 'tys.form.story.file.help_block'
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\Story'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_storytype';
    }
}
