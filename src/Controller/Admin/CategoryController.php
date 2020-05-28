<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\Admin\CategoryType;
use App\Messenger\Command\CreateCategoryCommand;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryController extends AbstractController
{
    private MessageBusInterface $messageBus;

    /**
     * CategoryController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $message
     */
    public function __construct(MessageBusInterface $message)
    {
        $this->messageBus = $message;
    }

    /**
     * @Route("/admin/categories", name="category_index", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\CategoryRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, CategoryRepository $repository): Response
    {
        $pagination = $repository->pagination(
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('admin/category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/categories/create", name="category_create", methods={"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request): Response
    {
        $command = new CreateCategoryCommand();
        $command->id = Uuid::uuid4()->toString();
        $form = $this->createForm(CategoryType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('category_index');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}