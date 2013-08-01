<?php

namespace EE\TYSBundle\Form;

use EE\TYSBundle\Validator\Constrains\Files;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Validator;

/**
 * Class AudioItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class AudioItemType extends AbstractType
{
    /**
     * @var Validator
     */
    protected $validator;

    function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

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
                'textarea',
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
                    'attr' => $this->addHtml5Validation(
                        array(
                            'placeholder' => 'tys.form.audioItem.uploadedFiles.placeholder',
                        ),
                        'uploadedFiles',
                        new \ReflectionClass($options['data_class'])
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

    public function addHtml5Validation(array $attr, $property, \ReflectionClass $class)
    {
        $validatedProperties = $this->validator
            ->getMetadataFor(new $class->name())
            ->properties;

        if ($property === 'uploadedFiles') {
            foreach ($validatedProperties[$property]->constraints as $constraint) {
                if ($constraint instanceof Files) {
                    $attr['accept'] = implode(',', $constraint->mimeTypes);
                }
            }
        }

        return $attr;
    }
}
