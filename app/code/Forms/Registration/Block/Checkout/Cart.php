<?php
namespace Forms\Registration\Block\Checkout;

use Magento\Framework\App\ResourceConnection;


class Cart extends \Magento\Framework\View\Element\Template
{

    protected $resource;
    protected $cart;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        ResourceConnection $resource,
        \Magento\Checkout\Model\Cart $cart,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;

        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();
        $this->cart = $cart;

        parent::__construct($context, $data);
    }

    /**
     * Get quote object associated with cart. By default it is current customer session quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuoteData()
    {   
        // return $this->cart->getQuote()->getItemsCollection();
        $this->_checkoutSession->getQuote();
        $this->_checkoutSession->getQuote()->collectTotals()->save();
        if (!$this->hasData('quote')) {
            $this->setData('quote', $this->_checkoutSession->getQuote());
        }
        return $this->_getData('quote');
    }


    // public function getPriceById($productId){
        
    // }

    public function getUserRewardPoints($user){

        $UserRewardPointsTbl = $this->connection->getTableName('user_rewards');   
        $getUserPointsSql = "SELECT * FROM " . $UserRewardPointsTbl . " WHERE customer_id ='".$user."'";
        $getUserPoints = $this->connection->fetchAll($getUserPointsSql);

        $credit = 0;
        $debit = 0;
        if(!empty($getUserPoints)){
            foreach($getUserPoints as $points){
            if($points['reward_type'] == 'credit'){
                $credit += $points['reward_points'];
            }elseif($points['reward_type'] == 'debit'){
                $debit += $points['reward_points'];
            }
        } 
        $earnedPoints = ($credit - $debit);
        return $earnedPoints;
        }else{
            return false;
        }
        


        
        // $getUserRewardPoints = $this->connection->fetchAll("SELECT SUM(reward_points) FROM ".$UserRewardPointsTbl." WHERE customer_id=".$user);

        // if(!empty($getUserRewardPoints)){
        //     foreach($getUserRewardPoints as $rewards){
        //         $reward_points =  $rewards['SUM(reward_points)'];
        //     }
        //     return $reward_points;
        // }else{
        //     return false;
        // }
    }
}