<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator;

/**
 * Class StoryType
 *
 * @author Konrad Podgórski <konrad.podgorski@gmail.com>
 */
class StoryType extends AbstractType
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
                'text',
                array(
                    'label' => 'tys.form.story.description.label',
                    'help_block' => 'tys.form.story.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story.description.placeholder',
                    )
                )
            )
            ->add(
                'tagline',
                null,
                array(
                    'label' => 'tys.form.story.tagline.label',
                    'help_block' => 'tys.form.story.tagline.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story.tagline.placeholder',
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
                'published',
                null,
                array(
                    'label' => 'tys.form.story.publish.label',
                    'help_block' => 'tys.form.story.publish.help_block',
                )
            )
            ->add(
                'coeditable',
                null,
                array(
                    'label' => 'tys.form.story.coeditable.label',
                    'help_block' => 'tys.form.story.coeditable.help_block',
                )
            )
            ->add(
                'file',
                new UploadType(),
                array(
                    'label' => 'tys.form.story.file.label',
                    'help_block' => 'tys.form.story.file.help_block',
                    'attr' => $this->addHtml5Validation(
                        array(
                            'class' => 'btn-tys',
                            'title' => 'tys.form.story.file.browse'
                        ),
                        'file',
                        new \ReflectionClass($options['data_class'])
                    )
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


        if ($property === 'file') {
            foreach ($validatedProperties[$property]->constraints as $constraint) {
                if ($constraint instanceof File) {
                    $attr['accept'] = implode(',', $constraint->mimeTypes);
                }
            }
        }

        return $attr;
    }
}
