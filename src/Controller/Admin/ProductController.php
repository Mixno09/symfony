<?php

declare(strict_type=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\ProductType;
use App\Repository\ProductRepository;
use App\UseCase\CreateProduct\Command as CreateCommand;
use App\UseCase\CreateProduct\Handler as CreateHandler;
use App\UseCase\DeleteProduct\Command as DeleteCommand;
use App\UseCase\DeleteProduct\Handler as DeleteHandler;
use App\UseCase\UpdateProduct\Command as UpdateCommand;
use App\UseCase\UpdateProduct\Handler as UpdateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
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
     * @param \App\UseCase\CreateProduct\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function create(Request $request, CreateHandler $handler): Response
    {
        $command = new CreateCommand();
        $form = $this->createForm(ProductType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $handler->execute($command);
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
     * @param \App\UseCase\UpdateProduct\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function update(int $id, Request $request, UpdateHandler $handler): Response
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
            $handler->execute($command);
            return $this->redirectToRoute('product_update', ['id' => $id]);
        }
        return $this->render('admin/product/update.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/product/{id}/delete", name="product_delete", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\UseCase\DeleteProduct\Handler $handler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function delete(int $id, Request $request, DeleteHandler $handler): Response
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
            $command->id = $id;
            $handler->execute($command);
            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/delete.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}