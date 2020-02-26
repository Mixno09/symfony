<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\UseCase\Product\CreateProduct\Command;
use App\UseCase\Product\CreateProduct\Handler;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

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

    protected function createNewEntity()
    {
        return new Command();
    }

    protected function persistEntity($command)
    {
        if ($command instanceof Command) {
            $this->handler->execute($command);
            return;
        }
        throw new \InvalidArgumentException(sprintf(
            'Аргумент $command должен быть экземпляром класса %s',
            Command::class
        ));
    }
}