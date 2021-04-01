<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $uploadsBaseUrl;

    public function __construct(string $uploadsBaseUrl)
    {
        $this->uploadsBaseUrl = $uploadsBaseUrl;
    }

//    public function getFilters(): array
//    {
//        return [
//            // If your filter generates SAFE HTML, you should add a third
//            // parameter: ['is_safe' => ['html']]
//            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//            new TwigFilter('filter_name', [$this, 'doSomething']),
//        ];
//    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_uploads', [$this, 'assetUploads']),
        ];
    }

    public function assetUploads(string $filename)
    {
        return $this->uploadsBaseUrl . '/' . $filename;
    }
}
