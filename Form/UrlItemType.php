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
            ->add(
                'url',
                'url',
                array(
                    'label' => 'tys.form.urlItem.url.label',
                    'help_block' => 'tys.form.urlItem.url.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.urlItem.url.placeholder',
                    )
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'tys.form.urlItem.name.label',
                    'help_block' => 'tys.form.urlItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.urlItem.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'tys.form.urlItem.description.label',
                    'help_block' => 'tys.form.urlItem.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.urlItem.description.placeholder',
                    )
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\UrlItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_urlitemtype';
    }
}
