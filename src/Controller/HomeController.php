<?php

namespace App\Controller;

use App\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(ProductRepositoryInterface $repository)
    {
        $products = $repository->last(9);
        return $this->render('home.html.twig', [
            'products' => $products,
        ]);
    }
}
