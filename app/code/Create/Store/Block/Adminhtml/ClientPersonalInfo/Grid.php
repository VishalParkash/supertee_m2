<?php
namespace Create\Store\Block\Adminhtml\ClientPersonalInfo;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
	protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
		\Create\Store\Model\ResourceModel\ClientPersonalInfo\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
		
		$this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
		
        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
       
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
		try{
			
			
			$collection =$this->_collectionFactory->load();

		  

			$this->setCollection($collection);

			parent::_prepareCollection();
		  
			return $this;
		}
		catch(Exception $e)
		{
			echo $e->getMessage();die;
		}
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog_product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
		$this->addColumn(
            'name',
            [
                'header' => __('name'),
                'index' => 'name',
                'class' => 'name'
            ]
        );
		$this->addColumn(
            'email',
            [
                'header' => __('email'),
                'index' => 'email',
                'class' => 'email'
            ]
        );
		$this->addColumn(
            'phone_number',
            [
                'header' => __('phone_number'),
                'index' => 'phone_number',
                'class' => 'phone_number'
            ]
        );
		$this->addColumn(
            'address',
            [
                'header' => __('address'),
                'index' => 'address',
                'class' => 'address'
            ]
        );
		$this->addColumn(
            'zipcode',
            [
                'header' => __('zipcode'),
                'index' => 'zipcode',
                'class' => 'zipcode'
            ]
        );
		$this->addColumn(
            'location',
            [
                'header' => __('location'),
                'index' => 'location',
                'class' => 'location'
            ]
        );
		$this->addColumn(
            'country',
            [
                'header' => __('country'),
                'index' => 'country',
                'class' => 'country'
            ]
        );
		$this->addColumn(
            'password',
            [
                'header' => __('password'),
                'index' => 'password',
                'class' => 'password'
            ]
        );
		$this->addColumn(
            'date_created',
            [
                'header' => __('date_created'),
                'index' => 'date_created',
                'type' => 'date',
            ]
        );
		$this->addColumn(
            'date_updated',
            [
                'header' => __('date_updated'),
                'index' => 'date_updated',
                'type' => 'date',
            ]
        );
		$this->addColumn(
            'storename',
            [
                'header' => __('storename'),
                'index' => 'storename',
                'class' => 'storename'
            ]
        );
		$this->addColumn(
            'storetype',
            [
                'header' => __('storetype'),
                'index' => 'storetype',
                'class' => 'storetype'
            ]
        );
		$this->addColumn(
            'storecategory',
            [
                'header' => __('storecategory'),
                'index' => 'storecategory',
                'class' => 'storecategory'
            ]
        );
		$this->addColumn(
            'iswebsiteavailable',
            [
                'header' => __('iswebsiteavailable'),
                'index' => 'iswebsiteavailable',
                'class' => 'iswebsiteavailable'
            ]
        );
		$this->addColumn(
            'storedomain',
            [
                'header' => __('storedomain'),
                'index' => 'storedomain',
                'class' => 'storedomain'
            ]
        );
		$this->addColumn(
            'issellingexperienced',
            [
                'header' => __('issellingexperienced'),
                'index' => 'issellingexperienced',
                'class' => 'issellingexperienced'
            ]
        );
		$this->addColumn(
            'monthlyvolume',
            [
                'header' => __('monthlyvolume'),
                'index' => 'monthlyvolume',
                'class' => 'monthlyvolume'
            ]
        );
		$this->addColumn(
            'mediumtocontact',
            [
                'header' => __('mediumtocontact'),
                'index' => 'mediumtocontact',
                'class' => 'mediumtocontact'
            ]
        );
		/*{{CedAddGridColumn}}*/

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

     /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('store/*/massDelete'),
                'confirm' => __('Are you sure?')
            )
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('store/*/index', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'store/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
