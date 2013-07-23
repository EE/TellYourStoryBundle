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
            ->add('name');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EE\TYSBundle\Entity\VideoItem'
        ));
    }

    public function getName()
    {
        return 'ee_tysbundle_videoitemtype';
    }
}
