<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Security\UserIdentity;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        UserRepositoryInterface $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userIdentity = UserIdentity::fromUser($user);
            $password = $form->get('plainPassword')->getData();
            // encode the plain password
            $user->password = $passwordEncoder->encodePassword($userIdentity, $password);

            $userRepository->save($user);
            // do anything else you need here, like send an email

            $userIdentity = UserIdentity::fromUser($user);
            return $guardHandler->authenticateUserAndHandleSuccess(
                $userIdentity,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
