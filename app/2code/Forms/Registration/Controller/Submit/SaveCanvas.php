<?php

namespace Forms\Registration\Controller\Submit;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class SaveCanvas extends Action {

    protected $resultJsonFactory;

    // public function __construct(
    //        // Context  $context

    // ) {

    //     // parent::__construct($context);
    // }


    public function execute() {
    /* @var \Magento\Framework\Controller\Result\Json $result */

        echo "<pre>";print_r($_POST);die;

        $resultJson = $this->resultJsonFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData(['success' => true]);
   } 
}