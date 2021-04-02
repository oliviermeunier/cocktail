<?php

namespace App\DataFixtures;
 
use App\Factory\CategoryFactory;
use App\Factory\CocktailFactory;
use App\Factory\CommentFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

class AppFixtures extends Fixture
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $uploadsBaseDir;

    public function __construct(Filesystem $filesystem, string $uploadsBaseDir)
    {
        $this->filesystem = $filesystem;
        $this->uploadsBaseDir = $uploadsBaseDir;
    }

    public function load(ObjectManager $manager)
    {
        // Suppression du dossier public/uploads
        $this->filesystem->remove($this->uploadsBaseDir);

        UserFactory::new()->createMany(20);
        UserFactory::createOne([
           'email' => 'admin@admin.com',
           'password' => 'admin',
           'roles' => ['ROLE_ADMIN']
        ]);

        CategoryFactory::new()->createMany(10);
        CocktailFactory::new()->createMany(15);

        CommentFactory::new()->createMany(100);

        $manager->flush();
    }
}
