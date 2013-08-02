<?php

namespace EE\TYSBundle\Form;

use EE\TYSBundle\Validator\Constrains\Files;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Validator;

/**
 * Class FileItemType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class FileItemType extends AbstractType
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
                    'attr' => $this->addHtml5Validation(
                        array(
                            'placeholder' => 'tys.form.fileItem.uploadedFiles.placeholder',
                            'title' => 'tys.form.fileItem.uploadedFiles.browse',
                            'class' => 'btn-tys'
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
                'data_class' => 'EE\TYSBundle\Entity\FileItem'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_fileitemtype';
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
