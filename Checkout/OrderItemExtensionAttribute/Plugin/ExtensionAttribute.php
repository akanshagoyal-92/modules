<?php

namespace Checkout\OrderItemExtensionAttribute\Plugin;

use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemExtensionInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Dlv\CableBuilder\Model\ServiceManager\ProductLength;

/**
 * Class OrderItemRepositoryPlugin
 */
class ExtensionAttribute
{
    /**
     * Order feedback field name
     */
    const FIELD_NAME = 'custom_attribute';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderItemExtensionFactory
     */
    protected $extensionFactory;

    /**
     * OrderItemRepositoryPlugin constructor
     *
     * @param OrderItemExtensionFactory $extensionFactory
     */
    public function __construct(
        OrderItemExtensionFactory $extensionFactory,
        ProductLength $productLength
    )
    {
        $this->extensionFactory = $extensionFactory;
        $this->productLength = $productLength;
    }

    /**
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemInterface $orderItem
     *
     * @return OrderItemInterface
     */
    public function afterGet(OrderItemRepositoryInterface $subject, OrderItemInterface $orderItem)
    {
        try {
            $extensionAttributes = $orderItem->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setCustomAttribute("Dummy Text");
            $orderItem->setExtensionAttributes($extensionAttributes);
        } catch(\Exception $e) {

        }

        return $orderItem;
    }

    /**
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderItemSearchResultInterface $searchResult
     *
     * @return OrderItemSearchResultInterface
     */
    public function afterGetList(OrderItemRepositoryInterface $subject, OrderItemSearchResultInterface $searchResult)
    {
        try {
            $orderItems = $searchResult->getItems();
            foreach ($orderItems as &$item) {
                $extensionAttributes = $item->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $extensionAttributes->setCustomAttribute("Dummy Text");
                $item->setExtensionAttributes($extensionAttributes);
            }
        } catch(\Exception $e) {

        }
        return $searchResult;
    }
}
