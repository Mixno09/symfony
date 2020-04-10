<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var \App\Repository\ProductRepository
     */
    private $repository;

    /**
     * HomeController constructor.
     * @param \App\Repository\ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function __invoke()
    {
        $products = $this->repository->newest(6);

        return $this->render('home.html.twig', [
            'products' => $products,
        ]);
    }
}
