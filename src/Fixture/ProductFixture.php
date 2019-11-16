<?php


namespace App\Fixture;


use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use App\Service\FileManager;
use Faker\Factory;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFixture extends AbstractFixture
{
    /**
     * @var \App\Service\FileManager
     */
    private $fileManager;
    /**
     * @var \App\Repository\ProductRepositoryInterface
     */
    private $repository;

    /**
     * ProductFixture constructor.
     * @param \App\Service\FileManager $fileManager
     * @param \App\Repository\ProductRepositoryInterface $repository
     */
    public function __construct(FileManager $fileManager, ProductRepositoryInterface $repository)
    {
        $this->fileManager = $fileManager;
        $this->repository = $repository;
    }

    public function load(array $options): void
    {
        $count = 20;
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
    }

    public function getName(): string
    {
        return 'product';
    }
}