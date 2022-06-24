<?php
namespace Website\NoindexNofollow\Model;

class Urls extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'noindex_nofollow_urls';

	protected $_cacheTag = 'noindex_nofollow_urls';

	protected $_eventPrefix = 'noindex_nofollow_urls';

	protected function _construct()
	{
		$this->_init('Website\NoindexNofollow\Model\ResourceModel\Urls');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];
		return $values;
	}
}
