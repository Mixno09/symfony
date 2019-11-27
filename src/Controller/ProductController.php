<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepositoryInterface;
use App\Service\FileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product", methods={"GET"}, requirements={"id"="\d+"})
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
     * @Route("/product/create", name="product.create", methods={"GET", "POST"})
     * @IsGranted("ROLE_PRODUCT_STORE")
     */
    public function create(Request $request, FileManager $fileManager, ProductRepositoryInterface $repository)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'validation_groups' => ['create', 'Default'],
            'method' => 'POST',
        ]);
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
     * @Route("/product/{id}/update", name="product.update", methods={"GET", "PUT"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_PRODUCT_UPDATE")
     */
    public function update(int $id, ProductRepositoryInterface $repository, Request $request, FileManager $fileManager)
    {
        $product = $repository->find($id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(ProductType::class, $product, ['method' => 'PUT']);
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

    /**
     * @Route("/product/{id}", name="product.delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_PRODUCT_DESTROY")
     */
    public function remove(int $id, Request $request, ProductRepositoryInterface $productRepository, FileManager $fileManager)
    {
        $token = $request->request->get('token');
        if (! $this->isCsrfTokenValid('product', $token)) {
            throw new HttpException(419);
        }
        $product = $productRepository->find($id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $productRepository->delete($id);
        $fileManager->delete($product->image);
        return $this->redirectToRoute('catalog');
    }
}
