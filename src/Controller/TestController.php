<?php

declare(strict_type=1);

namespace App\Controller;

use App\UseCase\Test\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class TestController extends AbstractController
{
    /**
     * @Route("/test")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request, MessageBusInterface $bus): Response
    {
        $command = new Command();
        $command->text = $request->query->get('text');
        $bus->dispatch($command);

        return $this->json('okay');
    }
}