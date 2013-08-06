<?php

namespace EE\TYSBundle\Form;

use EE\TYSBundle\Validator\Constraints\Files;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Validator;

/**
 * Class ImageItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class ImageItemType extends AbstractType
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
                    'label' => 'tys.form.imageItem.name.label',
                    'help_block' => 'tys.form.imageItem.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.imageItem.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'tys.form.imageItem.description.label',
                    'help_block' => 'tys.form.imageItem.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.imageItem.description.placeholder',
                    )
                )
            )
            ->add(
                'uploadedFiles',
                new UploadType(),
                array(
                    'label' => 'tys.form.imageItem.uploadedFiles.label',
                    'help_block' => 'tys.form.imageItem.uploadedFiles.help_block',
                    'attr' => $this->addHtml5Validation(
                        array(
                            'placeholder' => 'tys.form.imageItem.uploadedFiles.placeholder',
                            'title' => 'tys.form.imageItem.uploadedFiles.browse',
                            'class' => 'btn-tys'
                        ),
                        'uploadedFiles',
                        new \ReflectionClass($options['data_class'])
                    )
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\ImageItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_imageitemtype';
    }

    /**
     * @param array            $attr
     * @param                  $property
     * @param \ReflectionClass $class
     *
     * @return array
     */
    public function addHtml5Validation(array $attr, $property, \ReflectionClass $class)
    {
        $validatedProperties = $this->validator
            ->getMetadataFor(new $class->name())
            ->properties;


        if ($property === 'uploadedFiles' && array_key_exists('uploadedFiles', $validatedProperties)) {
            foreach ($validatedProperties[$property]->constraints as $constraint) {
                if ($constraint instanceof Files) {
                    $attr['accept'] = implode(',', $constraint->mimeTypes);
                }
            }
        }

        return $attr;
    }
}
