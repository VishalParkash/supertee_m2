<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forms\Registration\Controller\Bundle;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var ScopeConfigInterface
     */

    protected $request;

    protected $connection;

    protected $tierPrice;

    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $tierPrice
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->connection = $resource->getConnection();
        $this->tierPrice = $tierPrice;
    }

    public function execute(){

        $post = (array) $this->getRequest()->getPost();
        $productid = $post['pid'];
        $type = $post['type'];
        $currentProduct = $this->tierPrice->getById($productid);
        $tier_price = $currentProduct->getTierPrice();
        $allData = [];
        if ($post['type'] == 'allprice') {
            foreach ($tier_price as $keyval) {
                   $data['price'] = $keyval['price'];
                   $data['discount'] = round($keyval['percentage_value']);
                   $data['qty'] = round($keyval['price_qty']);
                   $allData [] = $data;                
            }
        } else {
            $min = (isset($tier_price[0]['price_qty']))?round($tier_price[0]['price_qty']):0;
            $max = 0;
            $max_default_val = round(max(array_column($tier_price, 'price_qty')));
            foreach ($tier_price as $keyval) {
                $max = $keyval['price_qty'];
                if ($post['qty'] >= $min && $post['qty'] <= $max) {
                   $data['min']  = $min;
                   $data['max']  = $keyval['price_qty'];
                   $data['price'] = $keyval['price'] * $post['qty'];
                   $data['discount'] = $keyval['percentage_value'];
                   $allData [] = $data;
                   break;
                } elseif($post['qty'] > $max_default_val) {
                    foreach ($tier_price as $keyval) {
                        if ($keyval['price_qty'] == $max_default_val) {
                            $data['price'] = $keyval['price'] * $post['qty'];
                            $data['discount'] = $keyval['percentage_value'];
                            $allData [] = $data;
                            break; 
                        }
                    }
                }
                $min = $keyval['price_qty']+1;
            }
        }
        
            
        // /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($allData);

        }
        
    

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
}

}