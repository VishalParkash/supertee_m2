<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forms\Registration\Controller\Wishlist;

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
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->connection = $resource->getConnection();
    }

    public function execute(){

        
        $post = (array) $this->getRequest()->getPost();
        $customerId = $post['cust_id'];
        $pid = $post['p_id'];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $wishlist = $objectManager->create('Magento\Wishlist\Model\Wishlist');
        $wishlistCollection = $wishlist->loadByCustomerId($customerId)->getItemCollection();
        $response = [
            'errors' => false,
            'pid' => $pid
        ];
        foreach ($wishlistCollection as $item) {
            if ($item->getProductId() == $pid) {
                $item->delete();
                $response = [
                    'errors' => true,
                    'pid' => $pid
                ];
                break;
            }
        }
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);

    }
        

}