<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\UseCase\Product\CreateProduct\Command as CreateProductCommand;
use App\UseCase\Product\CreateProduct\Handler as CreateProductHandler;
use App\UseCase\Product\UpdateProduct\Command as UpdateProductCommand;
use App\UseCase\Product\UpdateProduct\Handler as UpdateProductHandler;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use InvalidArgumentException;

class ProductController extends EasyAdminController
{
    /**
     * @var \App\UseCase\Product\CreateProduct\Handler
     */
    private $createProductHandler;

    /**
     * @var \App\UseCase\Product\UpdateProduct\Handler
     */
    private $updateProductHandler;

    /**
     * ProductController constructor.
     * @param \App\UseCase\Product\CreateProduct\Handler $createProductHandler
     * @param \App\UseCase\Product\UpdateProduct\Handler $updateProductHandler
     */
    public function __construct(CreateProductHandler $createProductHandler, UpdateProductHandler $updateProductHandler)
    {
        $this->createProductHandler = $createProductHandler;
        $this->updateProductHandler = $updateProductHandler;
    }

    protected function createNewEntity()
    {
        return new CreateProductCommand();
    }

    protected function persistEntity($command)
    {
        if (! $command instanceof CreateProductCommand) {
            throw new InvalidArgumentException(sprintf(
                'Аргумент $command должен быть экземпляром класса %s',
                CreateProductCommand::class
            ));
        }
        $this->createProductHandler->execute($command);
    }

    protected function editAction()
    {
        $easyadmin = $this->request->attributes->get('easyadmin');
        /** @var \App\Entity\Product $product */
        $product = $easyadmin['item'];

        $command = new UpdateProductCommand();
        $command->populate($product);

        $easyadmin['item'] = $command;
        $this->request->attributes->set('easyadmin', $easyadmin);

        return parent::editAction();
    }

    protected function updateEntity($command)
    {
        if (! $command instanceof UpdateProductCommand) {
            throw new InvalidArgumentException(sprintf(
                'Аргумент $command должен быть экземпляром класса %s',
                UpdateProductCommand::class
            ));
        }
        $this->updateProductHandler->execute($command);
    }
}