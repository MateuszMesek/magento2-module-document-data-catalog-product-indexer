<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer;

use ArrayIterator;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use MateuszMesek\DocumentDataIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexerApi\EntityIdsResolverInterface;
use Traversable;

class EntityIdsResolver implements EntityIdsResolverInterface
{
    private DimensionResolverInterface $storeIdResolver;
    private CollectionFactory $collectionFactory;

    public function __construct(
        DimensionResolverInterface $storeIdResolver,
        CollectionFactory $collectionFactory
    )
    {
        $this->storeIdResolver = $storeIdResolver;
        $this->collectionFactory = $collectionFactory;
    }

    public function resolve(array $dimensions): Traversable
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);

        $ids = array_map(
            'intval',
            $collection->getAllIds()
        );

        return new ArrayIterator($ids);
    }
}
