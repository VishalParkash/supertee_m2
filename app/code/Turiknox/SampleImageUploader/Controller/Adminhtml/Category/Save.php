<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Rohan Hapani
 */
namespace Turiknox\SampleImageUploader\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Auth\Session;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_adminSession;

    /**
     * @var \Rh\Blog\Model\BlogFactory
     */
    protected $categoryFactory;

    /**
     * @param Action\Context                      $context
     * @param \Magento\Backend\Model\Auth\Session $adminSession
     * @param \Rh\Blog\Model\BlogFactory          $blogFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Turiknox\SampleImageUploader\Model\CategoryFactory $categoryFactory
    ) {
        parent::__construct($context);
        $this->_adminSession = $adminSession;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $postObj = $this->getRequest()->getPostValue();
        $category = $postObj["category"];
        $date = date("Y-m-d");
        // $username = $this->_adminSession->getUser()->getFirstname();
        // if ($username == $name) {
        //     $username = $this->_adminSession->getUser()->getFirstname();
        // } else {
        //     $username = $name;
        // }

        $data = ["category" => $category];
        // $data = array_merge($postObj, $userDetail);

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->categoryFactory->create();
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_adminSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('category/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}