<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="", methods={"GET"})
     * @IsGranted("ROLE_USERS")
     */
    public function index(Request $request, UserRepositoryInterface $userRepository)
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $userRepository->paginate($page, 9);
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/{id}", name="_update", methods={"GET", "PUT"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_PRODUCT_UPDATE")
     */
    public function update(int $id, Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository)
    {
        $user = $userRepository->find($id);
        if (! $user instanceof User) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if (is_string($password)) {
                $user->updatePassword($password, $passwordEncoder);
            }
            $userRepository->save($user);
            return $this->redirectToRoute('user');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_remove", methods={"DELETE"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER_DESTROY")
     */
    public function remove(int $id, Request $request, UserRepositoryInterface $userRepository)
    {
        $token = $request->request->get('token');
        if (! $this->isCsrfTokenValid('user', $token)) {
            throw new HttpException(419);
        }
        $user = $userRepository->find($id);
        if (! $user instanceof User) {
            throw $this->createNotFoundException();
        }
        $userRepository->delete($id);
        return $this->redirectToRoute('user');
    }
}
