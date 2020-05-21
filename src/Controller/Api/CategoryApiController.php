<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\UseCase\CreateCategory\Command as CreateCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryApiController extends AbstractController
{
    private MessageBusInterface $messageBus;

    /**
     * CategoryApiController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/categories", name="api_category_create", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createCategory(Request $request): Response
    {
        $command = new CreateCommand();

        $data = $request->request->all();

        foreach ($data as $key => $value) {
            if (property_exists($command, $key)) {
                $command->{$key} = $value;
            }
        }
        $this->messageBus->dispatch($command);

    }
}