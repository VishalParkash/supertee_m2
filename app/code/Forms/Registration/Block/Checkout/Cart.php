<?php
namespace Forms\Registration\Block\Checkout;

use Magento\Framework\App\ResourceConnection;


class Cart extends \Magento\Framework\View\Element\Template
{

    protected $resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        ResourceConnection $resource,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;

        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();

        parent::__construct($context, $data);
    }

    /**
     * Get quote object associated with cart. By default it is current customer session quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuoteData()
    {
        $this->_checkoutSession->getQuote();
        if (!$this->hasData('quote')) {
            $this->setData('quote', $this->_checkoutSession->getQuote());
        }
        return $this->_getData('quote');
    }

    public function getUserRewardPoints($user){

        $UserRewardPointsTbl = $this->connection->getTableName('user_rewards');   
        $getUserRewardPoints = $this->connection->fetchAll("SELECT SUM(reward_points) FROM ".$UserRewardPointsTbl." WHERE customer_id=".$user);

        if(!empty($getUserRewardPoints)){
            foreach($getUserRewardPoints as $rewards){
                $reward_points =  $rewards['SUM(reward_points)'];
            }
            return $reward_points;
        }else{
            return false;
        }
    }
}