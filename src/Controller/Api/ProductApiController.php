<?php

namespace App\Controller\Api;

use App\UseCase\CreateProduct\Command as CreateCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductApiController extends AbstractController
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private $messageBus;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * ProductController constructor.
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     * @param $validator
     */
    public function __construct(MessageBusInterface $messageBus, ValidatorInterface $validator)
    {
        $this->messageBus = $messageBus;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/product", name="api_product_create", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request): Response
    {
        $command = new CreateCommand();

        $data = array_merge(
            $request->request->all(),
            $request->files->all()
        );

        foreach ($data as $key => $value) {
            if (property_exists($command, $key)) {
                $command->{$key} = $value;
            }
        }

        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            return $this->json([
                'type' => 'validation_error',
                'errors' => $this->errorsToJson($errors),
            ]);
        }

        $this->messageBus->dispatch($command);

        return $this->json([
            'type' => 'success',
        ]);

    }

    private function errorsToJson(ConstraintViolationListInterface $errors): array
    {
        $json = [];
        foreach ($errors as $error) {
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $error */
            $key = $error->getPropertyPath();
            $message = $error->getMessage();
            $json[$key][] = $message;
        }
        return $json;
    }
}
