<?php
namespace Website\NoindexNofollow\Model\ResourceModel\Urls;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'noindex_nofollow_urls_collection';
	protected $_eventObject = 'urls_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Website\NoindexNofollow\Model\Urls', 'Website\NoindexNofollow\Model\ResourceModel\Urls');
	}

}
