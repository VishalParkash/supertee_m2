<?php
namespace Forms\Registration\Block\Product;
use Magento\Backend\Block\Template\Context;

class Index extends \Magento\Framework\View\Element\Template
{
	public function __construct(Context $context,array $data = [])
    {
        parent::__construct($context, $data);
    }
    public function getFormAction()
        {
        return $this->getUrl('product/product/product', ['_secure' => true]);  
    }

    public function getImagePath()
{
   $imagePath = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
   return $imagePath .'BlankProduct/forDesign.png';
}

public function getCanvasUrl()
{
   return $this->getUrl('savecanvas/submit/savecanvas', ['_secure' => true]);
}

    // public function getstripeUrl()
    //     {
    //         \Stripe\Stripe::setApiKey('sk_test_51HIGV6Jy1OZGyp65YiZU9LgNo89qrU2tmtM7N1ghE3VNzuRA8EBQnubPpngp972bhiwJ7u4zc7b3FwSeK3bFbsp0005qZjjNJa');
    //         $account = \Stripe\Account::create([
    //            'type' => 'express'
    //         ]);
    //         $account_links = \Stripe\AccountLink::create([
    //           'account' => $account->id,
    //           'refresh_url' => 'https://dev.evantiv.com/magento/reauth/index/issue',
    //           'return_url' => 'https://dev.evantiv.com/magento/return/index/success',
    //           'type' => 'account_onboarding'
    //         ]);
    //         return $this->getUrl($account_links->url);  
    //     //return 'Hello';  
    // }
}