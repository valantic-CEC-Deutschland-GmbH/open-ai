<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \ValanticSpryker\Zed\OpenAi\Business\OpenAiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\OpenAi\Persistence\OpenAiRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\OpenAi\Communication\OpenAiCommunicationFactory getFactory()
 */
class OpenAiDeleteForm extends AbstractType
{
    public const FIELD_SUBMIT = 'submit';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(self::FIELD_SUBMIT, SubmitType::class, [
            'label' => 'Yes, delete this OpenAi Prompt',
            'attr' => [
                'class' => 'btn btn-danger safe-submit',
            ],
        ]);
    }
}
