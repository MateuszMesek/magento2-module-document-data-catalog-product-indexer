<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer;

use MateuszMesek\DocumentDataCatalogProduct\Command\GetDocumentDataByProductIdAndStoreId;
use MateuszMesek\DocumentDataIndexerApi\DataResolverInterface;
use MateuszMesek\DocumentDataIndexerApi\DimensionResolverInterface;
use Traversable;

class DataResolver implements DataResolverInterface
{
    private DimensionResolverInterface $storeIdResolver;
    private GetDocumentDataByProductIdAndStoreId $getDocumentDataByProductIdAndStoreId;

    public function __construct(
        DimensionResolverInterface $storeIdResolver,
        GetDocumentDataByProductIdAndStoreId $getDocumentDataByProductIdAndStoreId
    )
    {
        $this->storeIdResolver = $storeIdResolver;
        $this->getDocumentDataByProductIdAndStoreId = $getDocumentDataByProductIdAndStoreId;
    }

    public function resolve(array $dimensions, Traversable $entityIds): Traversable
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        foreach ($entityIds as $entityId) {
            yield $this->getDocumentDataByProductIdAndStoreId->execute($entityId, $storeId);
        }
    }
}
