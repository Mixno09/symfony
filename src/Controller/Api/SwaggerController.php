<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

final class SwaggerController extends AbstractController
{
    /**
     * @Route("/api", name="api", methods={"GET"})
     */
    public function swagger(): Response
    {
        return $this->render('swagger.html.twig');
    }

    /**
     * @Route("/api/swagger.yaml", name="swagger_yaml", methods={"GET"})
     * @param string $resourcesDir
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function specification(string $resourcesDir): Response
    {
        $response = $this->file("{$resourcesDir}/swagger.yaml", 'swagger.yaml', ResponseHeaderBag::DISPOSITION_INLINE);
        $response->headers->set('Cache-Control', ['no-cache', 'no-store', 'must-revalidate']);

        return $response;
    }
}