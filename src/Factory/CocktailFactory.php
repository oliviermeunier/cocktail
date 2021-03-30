<?php

namespace App\Factory;

use App\Entity\Cocktail;
use App\Repository\CocktailRepository;
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

    public function __construct(SluggerInterface $slugger)
    {
        parent::__construct();

        $this->slugger = $slugger;
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->sentence(3),
            'recipe' => self::faker()->text(500),
            'createdAt' => self::faker()->dateTimeBetween('-3 years'),
            'category' => CategoryFactory::random(),
            'image' => 'https://picsum.photos/seed/' . rand(0,500) . '/750/300'
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
             ->afterInstantiate(function(Cocktail $cocktail) {
                 $slug = $this->slugger->slug($cocktail->getName());
                 $cocktail->setSlug($slug);
             })
        ;
    }

    protected static function getClass(): string
    {
        return Cocktail::class;
    }
}
