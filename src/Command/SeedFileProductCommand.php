<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\FileProductRepository;
use App\Service\FileManager;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SeedFileProductCommand extends Command
{
    protected static $defaultName = 'seed:file-product';

    private $fileManager;
    private $repository;

    public function __construct(FileManager $fileManager, FileProductRepository $repository)
    {
        $this->fileManager = $fileManager;
        parent::__construct();
        $this->repository = $repository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Генерирует товары')
            ->addArgument('count', InputArgument::REQUIRED, 'Количество товаров');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');
        $count = filter_var($count, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (! is_int($count)) {
            $io->error('Параметр должен быть числом');
            return 0;
        }

        $faker = Factory::create('ru_RU');
        while ($count > 0) {
            $product = new Product();
            $product->title = $faker->sentence(3);
            $product->description = $faker->realText(300);
            $image = $faker->image(null, 400, 400);
            $uploadFile = new UploadedFile($image, basename($image), null, null, true);
            $image = $this->fileManager->upload($uploadFile);
            $product->image = $image;
            $this->repository->save($product);

            $count--;
        }
        $io->success('Товары сгенерированны');

        return 0;
    }
}
