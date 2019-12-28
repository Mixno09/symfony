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
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        $this->denyAccessUnlessGranted('create_review', $product);

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
        return $this->render('product_review/form.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{productId}/review/{reviewId}", name="product_review_update", requirements={"productId"="\d+", "reviewId"="\d+"}, methods={"GET", "PUT"})
     */
    public function update(int $productId, int $reviewId, Request $request, ProductRepositoryInterface $productRepository)
    {
        $product = $productRepository->find($productId);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $review = $product->getReview($reviewId);
        if (! $review instanceof Review) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted('update_review', $review);

        $form = $this->createForm(ReviewType::class, ['text' => $review->text], ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form->get('text')->getData();
            $review->update($text);
            $productRepository->save($product);
            return $this->redirectToRoute('product', ['id' => $product->id]);
        }
        return $this->render('product_review/form.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/{productId}/review/{reviewId}", name="product_review_delete", requirements={"productId"="\d+", "reviewId"="\d+"}, methods={"DELETE"})
     */
    public function delete(int $productId, int $reviewId, Request $request, ProductRepositoryInterface $productRepository)
    {
        $product = $productRepository->find($productId);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException();
        }
        $review = $product->getReview($reviewId);
        if (! $review instanceof Review) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted('delete_review', $review);
        $token = $request->request->get('token');
        if (! $this->isCsrfTokenValid('product_review', $token)) {
            throw new HttpException(419);
        }

        $product->deleteReview($review->id);
        $productRepository->save($product);

        return $this->redirectToRoute('product', ['id' => $product->id]);
    }
}
