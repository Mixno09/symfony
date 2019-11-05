<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepositoryInterface;
use App\Service\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", requirements={"id"="\d+"})
     */
    public function index(int $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/create", name="product.create")
     */
    public function create(Request $request, FileManager $fileManager, ProductRepositoryInterface $repository)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, ['validation_groups' => ['create', 'Default']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $image = $fileManager->upload($image);
            $product->image = $image;
            $repository->save($product);
            return $this->redirectToRoute('product', ['id' => $product->id]);
        }
        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{id}/update", name="product.update", requirements={"id"="\d+"})
     */
    public function update(int $id, ProductRepositoryInterface $repository, Request $request, FileManager $fileManager)
    {
        $product = $repository->find($id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $oldImage = null;
            if ($image instanceof UploadedFile) {
                $image = $fileManager->upload($image);
                $oldImage = $product->image;
                $product->image = $image;
            }
            $repository->save($product);
            if (is_string($oldImage)) {
                $fileManager->delete($oldImage);
            }
            return $this->redirectToRoute('product', ['id' => $product->id]);
        }
        return $this->render('product/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }
}
