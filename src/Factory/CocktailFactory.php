<?php

namespace App\Factory;

use App\Entity\Cocktail;
use App\Repository\CocktailRepository;
use App\Service\CocktailUploaderHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Cocktail|Proxy createOne(array $attributes = [])
 * @method static Cocktail[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Cocktail|Proxy find($criteria)
 * @method static Cocktail|Proxy findOrCreate(array $attributes)
 * @method static Cocktail|Proxy first(string $sortedField = 'id')
 * @method static Cocktail|Proxy last(string $sortedField = 'id')
 * @method static Cocktail|Proxy random(array $attributes = [])
 * @method static Cocktail|Proxy randomOrCreate(array $attributes = [])
 * @method static Cocktail[]|Proxy[] all()
 * @method static Cocktail[]|Proxy[] findBy(array $attributes)
 * @method static Cocktail[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Cocktail[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CocktailRepository|RepositoryProxy repository()
 * @method Cocktail|Proxy create($attributes = [])
 */
final class CocktailFactory extends ModelFactory
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    private $images = [
        'cocktail1.jpg',
        'cocktail2.jpg',
        'cocktail3.jpg',
        'cocktail4.jpg',
        'cocktail5.jpg',
        'cocktail6.jpg',
        'cocktail7.jpg',
        'cocktail8.jpg',
        'cocktail9.jpg',
        'cocktail10.jpg',
    ];

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CocktailUploaderHelper
     */
    private $uploaderHelper;

    public function __construct(SluggerInterface $slugger, Filesystem $filesystem, CocktailUploaderHelper $uploaderHelper)
    {
        parent::__construct();

        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->unique()->sentence(3),
            'recipe' => self::faker()->text(500),
            'createdAt' => self::faker()->dateTimeBetween('-3 years'),
            'category' => CategoryFactory::random(),
            'image' => self::faker()->randomElement($this->images),
            'user' => UserFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
             ->afterInstantiate(function(Cocktail $cocktail) {

                 // Slugification du nom du cocktail
                 $cocktail->setSlug($this->slugger->slug($cocktail->getName()));

                 // Upload de l'image
                 $source = __DIR__ . '/images/' . $cocktail->getImage();
                 $dest = sys_get_temp_dir() . $cocktail->getImage();
                 $this->filesystem->copy($source, $dest);

                 $this->uploaderHelper->uploadCocktailImage(new File($dest), $cocktail);
             })
        ;
    }

    protected static function getClass(): string
    {
        return Cocktail::class;
    }
}
