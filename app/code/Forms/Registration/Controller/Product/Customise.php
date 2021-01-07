<?php
namespace Forms\Registration\Controller\Product;

class Customise extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        // $store = $this->_storeManager->getStore();
        // $category = $this->categoryRepository->get(
        //     $store->getRootCategoryId()
        // );

        // $this->_coreRegistry->register('current_category', $category);

        // $page = $this->resultPageFactory->create();
        return $this->_pageFactory->create();
    }
}