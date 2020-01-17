<?php


namespace App\Fixture;

use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use App\Service\FileManager;
use Faker\Factory;
use Faker\Generator;
use RuntimeException;
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
            $image = $this->generateImage($faker);
            $image = $this->fileManager->upload($image);
            $product->image = $image;
            $this->repository->save($product);
            $count--;
        }
    }

    public function getName(): string
    {
        return 'product';
    }

    public function generateImage(Generator $faker): UploadedFile
    {
        $image = imagecreate(400, 400);
        if (! is_resource($image)) {
            throw new RuntimeException('Image is not resource');
        }

        $color = imagecolorallocate($image, ...$faker->rgbColorAsArray);
        if (! is_int($color)) {
            throw new RuntimeException('Color is not int');
        }

        $isFillImage = imagefill($image, 0,0, $color);
        if (! $isFillImage) {
            throw new RuntimeException('Image is not fill');
        }

        $file = tempnam(sys_get_temp_dir(), '');
        if (! is_string($file)) {
            throw new RuntimeException('File is not created');
        }

        $isSaveImage = imagejpeg($image, $file);
        if (! $isSaveImage) {
            throw new RuntimeException('Failed to save image');
        }

        $isDestroyImage = imagedestroy($image);
        if (! $isDestroyImage) {
            throw new RuntimeException('Failed to destroy image');
        }

        return new UploadedFile($file, basename($file), null, null, true);
    }
}
