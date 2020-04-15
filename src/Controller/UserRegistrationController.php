<?php

declare(strict_type=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

final class UserRegistrationController extends AbstractController
{
    /**
     * @Route("/user/registration", name="user_registration", methods={"GET"})
     */
    public function __invoke()
    {

    }
}