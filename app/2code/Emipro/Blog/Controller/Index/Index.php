<?php
namespace Emipro\Blog\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
   /**
  * Index Action*
  * @return void
  */
  public function execute()
  {
      $this->_view->loadLayout();
      $this->_view->renderLayout();
  }
}