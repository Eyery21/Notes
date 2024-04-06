<?php

namespace App\Twig;

use Symfony\Component\Form\FormFactoryInterface;
use App\Form\FormSearchType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('create_search_form', [$this, 'createSearchForm']),
        ];
    }

    public function createSearchForm()
    {
        $form = $this->formFactory->create(FormSearchType::class);
        return $form->createView();
    }
}
