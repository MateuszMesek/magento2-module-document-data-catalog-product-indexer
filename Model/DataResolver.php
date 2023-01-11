<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer\Model;

use MateuszMesek\DocumentDataCatalogProduct\Model\Command\GetDocumentDataByProductIdAndStoreId;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DataResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use Traversable;

class DataResolver implements DataResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface           $storeIdResolver,
        private readonly GetDocumentDataByProductIdAndStoreId $getDocumentDataByProductIdAndStoreId
    )
    {
    }

    public function resolve(array $dimensions, Traversable $entityIds): Traversable
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        foreach ($entityIds as $entityId) {
            $data = $this->getDocumentDataByProductIdAndStoreId->execute((int)$entityId, $storeId);

            yield $entityId => $data;
        }
    }
}
