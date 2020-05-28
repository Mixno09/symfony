<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Messenger\Command\CreateProductCommand;
use App\Repository\ProductRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductApiController extends AbstractController
{
    private MessageBusInterface $messageBus;

    /**
     * ProductController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/api/products", name="api_product_create", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(Request $request): Response
    {
        $command = new CreateProductCommand();

        $data = array_merge(
            $request->request->all(),
            $request->files->all()
        );

        foreach ($data as $key => $value) {
            if (property_exists($command, $key)) {
                $command->{$key} = $value;
            }
        }

        $this->messageBus->dispatch($command);

        $url = $this->generateUrl('api_product_get', ['id' => $command->id], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->json(['url' => $url], 201);
    }

    /**
     * @Route("/api/products/{id}", name="api_product_get", methods={"GET"})
     * @param \Ramsey\Uuid\UuidInterface $id
     * @param \App\Repository\ProductRepository $repository
     * @param \Symfony\Component\Asset\Packages $asset
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProduct(UuidInterface $id, ProductRepository $repository, Packages $asset): Response
    {
        $product = $repository->find($id);
        if (! $product instanceof Product) {
            throw $this->createNotFoundException("Продукта с ID={$id} не существует");
        }
        return $this->json([
            'id' => $product->getId(),
            'title' => (string) $product->getTitle(),
            'slug' => (string) $product->getSlug(),
            'description' => (string) $product->getDescription(),
            'image' => $asset->getUrl(
                $product->getImage()->getPath(),
                $product->getImage()->getPackageName()
            ),
        ]);
    }
}
