<?php
namespace Forms\Registration\Block\Setup;
use Magento\Backend\Block\Template\Context;
use Stripe;
class Page extends \Magento\Framework\View\Element\Template
{
	public function __construct(Context $context,
    \Forms\Registration\Model\Session $session,
      array $data = [])
    {
        $this->session = $session;
        parent::__construct($context, $data);
    }
    public function getFormAction()
        {
        return $this->getUrl('postvendor/submit/submit', ['_secure' => true]);  
    }

    public function getSessionData(){
        return $this->session->getData();
    }

    public function getstripeUrl()
        {
            \Stripe\Stripe::setApiKey('sk_test_51HIGV6Jy1OZGyp65YiZU9LgNo89qrU2tmtM7N1ghE3VNzuRA8EBQnubPpngp972bhiwJ7u4zc7b3FwSeK3bFbsp0005qZjjNJa');
            $account = \Stripe\Account::create([
               'type' => 'express'
            ]);
            $account_links = \Stripe\AccountLink::create([
              'account' => $account->id,
              'refresh_url' => 'https://dev.evantiv.com/magento/reauth/index/issue',
              'return_url' => 'https://dev.evantiv.com/magento/return/index/success',
              'type' => 'account_onboarding'
            ]);
            return $this->getUrl($account_links->url);  
        //return 'Hello';  
    }
}