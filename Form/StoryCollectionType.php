<?php

namespace EE\TYSBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class StoryCollectionType
 *
 * @author Jarek Rencz <jarek.rencz@laboratorium.ee>
 */
class StoryCollectionType extends AbstractType
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param Validator $validator
     * @param ContainerInterface $container
     */
    public function __construct(Validator $validator, ContainerInterface $container)
    {
        $this->validator = $validator;
        $this->container = $container;
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
            )
            /**
             * This is a workaround: Doctrine does not seem to handle cascades on the inverse side of the relationship.
             * THis way it's handled manually.
             */
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $em = $this->container->get('doctrine.orm.entity_manager');
                $storyCollection = $event->getForm()->getData();
                $eventData = $event->getData();

                $newStories = array_map(function ($id) {
                    return (int) $id;
                }, array_key_exists('stories', $eventData) ? $eventData['stories'] : array());
                $oldStories = $storyCollection
                    ->getStories()
                    ->map(function ($story) {
                        return $story->getId();
                    })
                    ->toArray();

                foreach (array_diff($newStories, $oldStories) as $id) {
                    $story = $em->getRepository('EETYSBundle:Story')->find($id);
                    if ($story) {
                        $storyCollection->getStories()->add($story);
                        $story->addStoryCollection($storyCollection);
                    }
                }

                foreach (array_diff($oldStories, $newStories) as $id) {
                    $story = $em->getRepository('EETYSBundle:Story')->find($id);
                    if ($story) {
                        $storyCollection->getStories()->removeElement($story);
                        $story->removeStoryCollection($storyCollection);
                    }
                }
            });
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
