<?php

namespace App\Controller;

use App\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    /**
     * @Route("/catalog", name="catalog")
     */
    public function index(Request $request, ProductRepositoryInterface $repository)
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $repository->paginate($page, 3);

        return $this->render('catalog.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
