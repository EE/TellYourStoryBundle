<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TextItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class TextItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                null,
                array(
                    'label' => 'tys.form.textItem.name.label',
                    'help_block' => 'tys.form.textItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.textItem.name.placeholder',
                    )
                )
            )
            ->add(
                'body',
                null,
                array(
                    'label' => 'tys.form.textItem.body.label',
                    'help_block' => 'tys.form.textItem.body.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.textItem.body.placeholder',
                    )
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\TextItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_textitemtype';
    }
}
