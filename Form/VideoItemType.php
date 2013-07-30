<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class VideoItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class VideoItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'url',
                'url',
                array(
                    'label' => 'tys.form.videoItem.url.label',
                    'help_block' => 'tys.form.videoItem.url.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.videoItem.url.placeholder',
                    )
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'tys.form.videoItem.name.label',
                    'help_block' => 'tys.form.videoItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.videoItem.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                'textarea',
                array(
                    'label' => 'tys.form.videoItem.description.label',
                    'help_block' => 'tys.form.videoItem.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.videoItem.description.placeholder',
                    )
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\VideoItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_videoitemtype';
    }
}
