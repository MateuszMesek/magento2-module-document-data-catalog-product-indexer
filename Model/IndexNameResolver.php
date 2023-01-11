<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNameResolverInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $storeIdResolver
    )
    {
    }

    public function resolve(array $dimensions): string
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        return "product_$storeId";
    }
}
