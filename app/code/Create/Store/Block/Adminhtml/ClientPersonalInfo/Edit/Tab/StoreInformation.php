<?php
namespace Create\Store\Block\Adminhtml\ClientPersonalInfo\Edit\Tab;
class StoreInformation extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
		/* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('store_clientpersonalinfo');
		$isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Store Information')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

		$fieldset->addField(
            'name',
            'text',
            array(
                'name' => 'name',
                'label' => __('client'),
                'title' => __('client'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'storename',
            'text',
            array(
                'name' => 'storename',
                'label' => __('store'),
                'title' => __('store'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'storecategory',
            'text',
            array(
                'name' => 'storecategory',
                'label' => __('category'),
                'title' => __('category'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'storetype',
            'text',
            array(
                'name' => 'storetype',
                'label' => __('store type'),
                'title' => __('store type'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'monthlyvolume',
            'text',
            array(
                'name' => 'monthlyvolume',
                'label' => __('monthly volume'),
                'title' => __('monthly volume'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'storedomain',
            'text',
            array(
                'name' => 'storedomain',
                'label' => __('domain name'),
                'title' => __('domain name'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'email',
            'text',
            array(
                'name' => 'email',
                'label' => __('email'),
                'title' => __('email'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'phone_number',
            'text',
            array(
                'name' => 'phone_number',
                'label' => __('phone'),
                'title' => __('phone'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'password',
            'text',
            array(
                'name' => 'password',
                'label' => __('password'),
                'title' => __('password'),
                /*'required' => true,*/
            )
        );
		/*{{CedAddFormField}}*/
        
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();   
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Store Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Store Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
