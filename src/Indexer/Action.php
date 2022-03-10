<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataCatalogProductIndexer\Indexer;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Store\Model\StoreDimensionProvider;
use MateuszMesek\DocumentDataCatalogProduct\Command\GetDocumentDataByProductIdAndStoreId;

class Action implements ActionInterface, DimensionalIndexerInterface, \Magento\Framework\Mview\ActionInterface
{
    private CollectionFactory $collectionFactory;
    private DimensionProviderInterface $dimensionProvider;
    private GetDocumentDataByProductIdAndStoreId $getDocumentDataByProductIdAndStoreId;

    public function __construct(
        CollectionFactory                     $collectionFactory,
        DimensionProviderInterface            $dimensionProvider,
        GetDocumentDataByProductIdAndStoreId $getDocumentDataByProductIdAndStoreId
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->dimensionProvider = $dimensionProvider;
        $this->getDocumentDataByProductIdAndStoreId = $getDocumentDataByProductIdAndStoreId;
    }

    public function executeFull()
    {
        $collection = $this->collectionFactory->create();
        $collection->setStoreId(0);

        $ids = $collection->getAllIds();

        $this->execute($ids);
    }

    public function executeList(array $ids)
    {
        $this->execute($ids);
    }

    public function executeRow($id)
    {
        $this->execute([$id]);
    }

    public function execute($ids)
    {
        var_dump('ids:'.count($ids));

        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new \ArrayIterator($ids));
        }
    }

    public function executeByDimensions(array $dimensions, \Traversable $entityIds)
    {
        $storeId = (int)($dimensions[StoreDimensionProvider::DIMENSION_NAME]->getValue() ?? 0);

        var_dump('storeId:'.$storeId);
        $t = microtime(true);

        foreach ($entityIds as $entityId) {
            $data = $this->getDocumentDataByProductIdAndStoreId->execute((int)$entityId, $storeId);
        }

        var_dump(number_format(microtime(true) - $t, 4).'s');
    }
}
