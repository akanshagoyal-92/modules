<?php
namespace Website\NoindexNofollow\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddNoindexfollow implements ObserverInterface
{
    protected $request;

    protected $layoutFactory;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Page\Config $layoutFactory,
        \Magento\Framework\UrlInterface $urlInterface,
        \Website\NoindexNofollow\Model\ResourceModel\Urls\Collection $urlCollection
    ) {
        $this->request = $request;
        $this->layoutFactory = $layoutFactory;
        $this->_urlInterface = $urlInterface;
        $this->_urlCollection = $urlCollection;
    }

    public function execute(Observer $observer)
    {
        $currenturl     =   $this->_urlInterface->getCurrentUrl();
        $baseUrl        =   $this->_urlInterface->getBaseUrl();
        $urlKey         =   str_replace($baseUrl,'',$currenturl);
        $urlData        =   $this->_urlCollection->addFieldToFilter('url',['eq' => $urlKey]);

        if ($urlData->getSize() > 0) {
            $this->layoutFactory->setRobots('NOINDEX,NOFOLLOW');
        }
    }
}
