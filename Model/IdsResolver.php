<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer\Model;

use ArrayIterator;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use Traversable;

class IdsResolver implements IdsResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $storeIdResolver,
        private readonly CollectionFactory          $collectionFactory
    )
    {
    }

    public function resolve(array $dimensions): Traversable
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        $collection = $this->collectionFactory->create();
        $collection->addStoreFilter($storeId);

        $ids = array_map(
            'intval',
            $collection->getAllIds()
        );

        var_dump(count($ids));

        return new ArrayIterator($ids);
    }
}
