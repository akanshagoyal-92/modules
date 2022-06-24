<?php
namespace Website\NoindexNofollow\Model\ResourceModel;

class Urls extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	){
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('noindex_nofollow_urls', 'id');
	}
}
