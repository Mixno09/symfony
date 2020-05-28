<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\Messenger\Command\DeleteProductCommand;
use App\Messenger\Command\UpdateProductCommand;
use App\Repository\ProductRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    private MessageBusInterface $messageBus;

    /**
     * ProductController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/admin/products", name="product_index", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\ProductRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, ProductRepository $repository): Response
    {
        $pagination = $repository->pagination(
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('admin/product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/admin/products/create", name="product_create", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(): Response
    {
        return $this->render('admin/product/create.html.twig');
    }

    /**
     * @Route("/admin/products/{id}/update", name="product_update", methods={"GET", "POST"})
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UuidInterface $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException("Продукта с ID={$id} не существует");
        }

        $command = new UpdateProductCommand();
        $command->populate($product);
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('product_update', ['id' => $id]);
        }
        return $this->render('admin/product/update.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/products/{id}/delete", name="product_delete", methods={"GET", "POST"})
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(UuidInterface $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException("Продукта с ID={$id} не существует");
        }

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = new DeleteProductCommand();
            $command->id = $product->getId()->toString();
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/delete.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}