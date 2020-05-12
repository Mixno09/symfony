<?php

declare(strict_type=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CatalogController extends AbstractController
{
    private ProductRepository $repository;

    /**
     * CatalogController constructor.
     * @param \App\Repository\ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/catalog", name="catalog", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->repository->pagination($page, 9);
        return $this->render('product/catalog.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}