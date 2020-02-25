<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\UseCase\Product\CreateProduct\Command;
use App\UseCase\Product\CreateProduct\Handler;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\Form\DataMapperInterface;

class ProductController extends EasyAdminController
{
    /**
     * @var \App\UseCase\Product\CreateProduct\Handler
     */
    private $handler;

    /**
     * ProductController constructor.
     * @param \App\UseCase\Product\CreateProduct\Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    protected function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->createNewForm(null, $fields);

        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->processUploadedFiles($newForm);
            /** @var Command $command */
            $command = $newForm->getData();
            $this->handler->execute($command);

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, [
            'entity_fields' => $fields,
            'form' => $newForm,
        ]);

        $parameters = [
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
        ];

        return $this->renderTemplate('new', $this->entity['templates']['new'], $parameters);
    }

    protected function createNewForm($entity, array $entityProperties)
    {
        $formBuilder = $this->createEntityFormBuilder($entity, 'new');

        $formBuilder->setDataMapper(new class implements DataMapperInterface {

            /**
             * @inheritDoc
             */
            public function mapDataToForms($viewData, iterable $forms)
            {
            }

            /**
             * @inheritDoc
             */
            public function mapFormsToData(iterable $forms, &$viewData)
            {
                /** @noinspection PhpParamsInspection */
                $forms = iterator_to_array($forms);
                /** @var \Symfony\Component\Form\Form[] $forms */

                $viewData = new Command(
                    $forms['title']->getData(),
                    $forms['description']->getData(),
                    $forms['image']->getData()
                );
            }
        });

        $formBuilder->setEmptyData(null);

        return $formBuilder->getForm();
    }
}