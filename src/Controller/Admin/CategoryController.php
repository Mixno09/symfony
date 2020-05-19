<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="category_index", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\CategoryRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, CategoryRepository $repository): Response
    {
        $pagination = $repository->pagination(
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('admin/category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}