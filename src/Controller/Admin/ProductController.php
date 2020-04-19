<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\Repository\ProductRepository;
use App\UseCase\CreateProduct\Command as CreateCommand;
use App\UseCase\DeleteProduct\Command as DeleteCommand;
use App\UseCase\UpdateProduct\Command as UpdateCommand;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private $messageBus;

    /**
     * ProductController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/admin/product", name="product_index", methods={"GET"})
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
     * @Route("/admin/product/create", name="product_create", methods={"GET", "POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request): Response
    {
        $command = new CreateCommand();
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = Uuid::uuid4();
            $command->id = $id->toString();
            $this->messageBus->dispatch($command);
            return $this->redirectToRoute('product_update', ['id' => $id]);
        }
        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/{id}/update", name="product_update", methods={"GET", "POST"})
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

        $command = new UpdateCommand();
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
     * @Route("/admin/product/{id}/delete", name="product_delete", methods={"GET", "POST"})
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
            $command = new DeleteCommand();
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