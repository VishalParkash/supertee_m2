<?php
 
namespace Emipro\Blog\Block\Adminhtml\Grid\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_adminSession;
   
    protected $_wysiwygConfig;
    
    protected $_status;
 
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Emipro\Blog\Model\Status $status,        
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_status = $status;         
        parent::__construct($context, $registry, $formFactory, $data);
         $this->_adminSession = $adminSession;     
       
    }
 
   
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('emipro_form_data');
 
        $isElementDisabled = false;
 
     
        $form = $this->_formFactory->create();
 
        $form->setHtmlIdPrefix('page_');
 
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Blog Information')]);
 
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
 

         $fieldset->addField(
            'name',
            'file',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'value' => $this->_adminSession->getUser()->getFirstname(),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Blog Title'),
                'title' => __('Blog Title'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
          

       
 
        $contentField = $fieldset->addField(
            'content',
            'textarea',            
            [
                'name' => 'content',
                 'label' => __('Content'),
                 'title' => __('Content'),
               // 'style' => 'height:36em;',
                'required' => true,
                'disabled' => $isElementDisabled
                //'config' => $wysiwygConfig
            ]
        );
 
      
        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );
 
      
 
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => $this->_status->getOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }
 
        $form->addValues($model->getData());
     
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
 
   
    public function getTabLabel()
    {
        return __('Blog Information');
    }
 
   
    public function getTabTitle()
    {
        return __('Blog Information');
    }
 
 
    public function canShowTab()
    {
        return true;
    }
 
 
    public function isHidden()
    {
        return false;
    }
 
   
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}