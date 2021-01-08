<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Vishal Parkash
 */
namespace Turiknox\SampleImageUploader\Block\Adminhtml;

/**
 * Backend grid container block
 */
class Category extends \Magento\Backend\Block\Widget\Container
{

    /**
     * @var string
     */
    protected $_template = 'category.phtml';

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {

        $addButtonProps = [
            'id' => 'add_new_category',
            'label' => __('Add New'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'options' => $this->_getAddButtonOptions(),
        ];

        $this->buttonList->add('add_new', $addButtonProps);

        $this->setChild('grid', $this->getLayout()->createBlock('Turiknox\SampleImageUploader\Block\Adminhtml\Category\Category', 'category.view.category'));
        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    protected function _getAddButtonOptions()
    {

        $splitButtonOptions[] = [
            'label' => __('Add New'),
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')",
        ];
        return $splitButtonOptions;
    }

    /**
     * @return string
     */
    protected function _getCreateUrl()
    {
        return $this->getUrl('category/*/new');
    }

    /**
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('category');
    }
}