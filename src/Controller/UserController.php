<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user", methods={"GET"})
     */
    public function index(UserRepositoryInterface $userRepository, Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $userRepository->paginate($page, 9);
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_update", methods={"GET", "PUT"}, requirements={"id"="\d+"})
     */
    public function update(int $id, UserRepositoryInterface $userRepository)
    {
        $user = $userRepository->find($id);
        if (! $user instanceof User) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(UserType::class, $user);

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
