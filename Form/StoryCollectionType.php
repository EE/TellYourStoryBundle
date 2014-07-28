<?php

namespace EE\TYSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator;

/**
 * Class StoryCollectionType
 *
 * @author Jarek Rencz <jarek.rencz@gmail.com>
 */
class StoryCollectionType extends AbstractType
{
    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(Validator $validator)
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
                    'label' => 'tys.form.story_collection.name.label',
                    'help_block' => 'tys.form.story_collection.name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story_collection.name.placeholder',
                    )
                )
            )
            ->add(
                'description',
                'text',
                array(
                    'label' => 'tys.form.story_collection.description.label',
                    'help_block' => 'tys.form.story_collection.description.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story_collection.description.placeholder',
                    )
                )
            )
            ->add(
                'organizationName',
                null,
                array(
                    'label' => 'tys.form.story_collection.organization_name.label',
                    'help_block' => 'tys.form.story_collection.organization_name.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story_collection.organization_name.placeholder',
                    )
                )
            )
            ->add(
                'tagline',
                null,
                array(
                    'label' => 'tys.form.story_collection.tagline.label',
                    'help_block' => 'tys.form.story_collection.tagline.help_block',
                    'attr' => array(
                        'placeholder' => 'tys.form.story_collection.tagline.placeholder',
                    )
                )
            )
            ->add(
                'file',
                new UploadType(),
                array(
                    'label' => 'tys.form.story_collection.file.label',
                    'help_block' => 'tys.form.story_collection.file.help_block',
                    'attr' => $this->addHtml5Validation(
                        array(
                            'class' => 'btn-tys',
                            'title' => 'tys.form.story_collection.file.browse'
                        ),
                        'file',
                        new \ReflectionClass($options['data_class'])
                    )
                )
            )
            ->add(
                'stories',
                'entity',
                array(
                    'label' => 'tys.form.story_collection.stories.label',
                    'help_block' => 'tys.form.story_collection.stories.help_block',
                    'class' => 'EE\TYSBundle\Entity\Story',
                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EE\TYSBundle\Entity\StoryCollection'
            )
        );
    }

    public function getName()
    {
        return 'ee_tysbundle_storycollectiontype';
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
