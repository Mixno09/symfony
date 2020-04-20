<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var \App\Repository\ProductRepository
     */
    private $productRepository;

    /**
     * ProductController constructor.
     * @param \App\Repository\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/product/{slug}", name="product", methods={"GET"})
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(string $slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
