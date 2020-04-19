<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductController constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/product/{id}", name="product", methods={"GET"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(string $id)
    {
        $product = $this->entityManager->find(Product::class, $id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
