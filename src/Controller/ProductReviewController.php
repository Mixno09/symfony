<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use App\Repository\ProductRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductReviewController extends AbstractController
{
    /**
     * @Route("/product/{productId}/review", name="product_review", requirements={"productId"="\d+"}, methods={"GET", "POST"})
     */
    public function create(int $productId, Request $request, ProductRepositoryInterface $productRepository, UserRepositoryInterface $userRepository)
    {
        $product = $productRepository->find($productId);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $userIdentity = $this->getUser();
        if (! $userIdentity instanceof UserIdentity) {
            throw $this->createAccessDeniedException();
        }
        $email = $userIdentity->getUsername();
        $user = $userRepository->getByEmail($email);
        if (! $user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form->get('text')->getData();
            $review = new Review($user, $text);
            $product->addReview($review);
            $productRepository->save($product);
            return $this->redirectToRoute('product', ['id' => $product->id]);
        }
        return $this->render('product_review/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
