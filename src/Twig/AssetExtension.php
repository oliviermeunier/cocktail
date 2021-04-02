<?php

namespace App\Twig;

use App\Service\UploaderHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_uploads', [$this, 'assetUploads']),
        ];
    }

    public function assetUploads(string $filename)
    {
        return $this->uploaderHelper->assetUploadUrl($filename);
    }
}
