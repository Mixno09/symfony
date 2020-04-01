<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\UseCase\CreateProduct\Command as CreateCommand;
use App\UseCase\CreateProduct\Handler as CreateHandler;
use App\UseCase\UpdateProduct\Command as UpdateCommand;
use App\UseCase\UpdateProduct\Handler as UpdateHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    /**
     * @var \App\UseCase\CreateProduct\Handler
     */
    private $createHandler;
    /**
     * @var UpdateHandler
     */
    private $updateHandler;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductController constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\UseCase\CreateProduct\Handler $createHandler
     * @param \App\UseCase\UpdateProduct\Handler $updateHandler
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CreateHandler $createHandler,
        UpdateHandler $updateHandler
    ) {
        $this->createHandler = $createHandler;
        $this->entityManager = $entityManager;
        $this->updateHandler = $updateHandler;
    }

    /**
     * @Route("/admin/product/create", name="product_create", methods={"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function create(Request $request): Response
    {
        $command = new CreateCommand();
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->createHandler->execute($command);
            return $this->redirectToRoute('product_update', ['id' => $id]);
        }
        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/{id}/update", name="product_update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function update(int $id, Request $request): Response
    {
        $product = $this->entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException("Продукта с ID={$id} не существует");
        }

        $command = new UpdateCommand();
        $command->populate($product);
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateHandler->execute($command);
            return $this->redirectToRoute('product_update', ['id' => $id]);
        }
        return $this->render('admin/product/update.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }
}