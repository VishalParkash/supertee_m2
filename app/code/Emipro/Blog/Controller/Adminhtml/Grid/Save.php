<?php
namespace Emipro\Blog\Controller\Adminhtml\Grid;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{

    protected $_adminSession;
    protected $csv;
    
    public function __construct(Action\Context $context,\Magento\Backend\Model\Auth\Session $adminSession,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csv
        )
    {       
        parent::__construct($context);
        $this->_adminSession = $adminSession;  
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);    
        $this->csv = $csv;
    }

    public function execute()
    {
       // echo "<pre>";print_r($_FILES);die;
        if (!isset($_FILES['name']['tmp_name'])) 
        throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
 
     $csvData = $this->csv->getData($_FILES['name']['tmp_name']);
     // echo "<pre>";print_r($csvData);die;
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $configurable_product = $objectManager->create('\Magento\Catalog\Model\Product');

    $headerCount = 1;
     foreach ($csvData as $header) {
            if($headerCount == 1){
                $headerColumn =  $header;
                break;
            } 
     }
     array_shift($csvData);
     // echo "<pre>";print_r($headerColumn);
     // die;
     $name = date('m_d_Y_H_i_s');
        $filepath = 'export/custom' . $name . '.csv';
        $this->directory->create('export');
        /* Open file */
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        $header = array();
        // $columns = $this->getColumnHeader();
        foreach ($headerColumn as $column) {
            if($column == 'styleID'){
                $column = '';
            }if($column == 'brandName'){
                $column = '';
            }if($column == 'colorName'){
                $column = '';
            }if($column == 'colorFrontImage'){
                $column = '';
            }if($column == 'colorSideImage'){
                $column = '';
            }if($column == 'colorBackImage'){
                $column = '';
            }
            // unset($column[14]);
            // unset($column[15]);
            // unset($column[16]);
            // unset($column[19]);
            // unset($column[20]);
            // unset($column[21]);
            if(!empty($column)){
                if($column == 'prodtype'){
                $column = 'product_type';
            }if($column == 'attribute_set'){
                $column = 'attribute_set_code';
            }if($column == 'category'){
                $column = 'categories';
            }
            $header[] = $column;
            }
            
        }
        // die;
        // echo "<pre>";print_r($header);die;
        /* Write Header */
        $stream->writeCsv($header);

        $newArray = array();
        $finalArray = array();
        $condArray = array();
 
        // $products[] = array(1,'Test 1','test 1',100);
        // $products[] = array(2,'Test 2','test 2',299);
        foreach($csvData as $newData){
            $condArray = array();
            $newArray = array();
            unset($newData[18]);
            unset($newData[32]);
            $productName = $newData[10];
            $explodeSize = explode("/", $newData[25]);
            $explodeColor = explode("/", $newData[16]);
                
            if(!empty($newData[25])) {

                $newData[3] = 'configurable';
                $newData[37] = "color=".$explodeColor[0].",brand=".$newData[15].",size=".$explodeSize[0];
                $newArray[] = $newData[0];
                $newArray[] = $newData[1];
                $newArray[] = $newData[2];
                $newArray[] = $newData[3];
                $newArray[] = $newData[4];
                $newArray[] = $newData[5];
                $newArray[] = $newData[6];
                $newArray[] = $newData[7];
                $newArray[] = $newData[8];
                $newArray[] = $newData[9];
                $newArray[] = $newData[10];
                $newArray[] = $newData[11];
                $newArray[] = $newData[12];
                $newArray[] = $newData[13];
                $newArray[] = $newData[14];
                $newArray[] = $newData[15];
                $newArray[] = $newData[16];
                $newArray[] = $newData[17];
                // $newArray[] = $newData[18];
                $newArray[] = $newData[19];
                $newArray[] = $newData[20];
                $newArray[] = $newData[21];
                $newArray[] = $newData[22];
                $newArray[] = $newData[23];
                $newArray[] = $newData[24];
                $newArray[] = $newData[25];
                $newArray[] = $newData[26];
                $newArray[] = $newData[27];
                $newArray[] = $newData[28];
                $newArray[] = $newData[29];
                $newArray[] = $newData[30];
                $newArray[] = $newData[31];
                // $newArray[] = $newData[32];
                $newArray[] = $newData[33];
                $newArray[] = $newData[34];
                $newArray[] = $newData[35];
                $newArray[] = $newData[36];
                $newArray[] = $newData[37];
        
                $countForAttr = (count($explodeSize) * count($explodeColor));

                for($i=0;$i<count($explodeSize);$i++){
                    $condArray = array();

                    $name = '';
                    if(!empty($explodeSize[$i])){
                        $newData[3] = 'simple';

                        for($j=0;$j<count($explodeColor); $j++){
                            $name = '';
                            $name = $productName."-".$explodeColor[$j]."-".$explodeSize[$i];
                            $newData[10] = $name;
                            $newData[37] = "color=".$explodeColor[$j].",brand=".$newData[15].",size=".$explodeSize[$i];

                            $condArray[] = $newData[0];
                            $condArray[] = $newData[1];
                            $condArray[] = $newData[2];
                            $condArray[] = $newData[3];
                            $condArray[] = $newData[4];
                            $condArray[] = $newData[5];
                            $condArray[] = $newData[6];
                            $condArray[] = $newData[7];
                            $condArray[] = $newData[8];
                            $condArray[] = $newData[9];
                            $condArray[] = $newData[10];
                            $condArray[] = $newData[11];
                            $condArray[] = $newData[12];
                            $condArray[] = $newData[13];
                            $condArray[] = $newData[14];
                            $condArray[] = $newData[15];
                            $condArray[] = $newData[16];
                            $condArray[] = $newData[17];
                            // $condArray[] = $newData[18];
                            $condArray[] = $newData[19];
                            $condArray[] = $newData[20];
                            $condArray[] = $newData[21];
                            $condArray[] = $newData[22];
                            $condArray[] = $newData[23];
                            $condArray[] = $newData[24];
                            $condArray[] = $newData[25];
                            $condArray[] = $newData[26];
                            $condArray[] = $newData[27];
                            $condArray[] = $newData[28];
                            $condArray[] = $newData[29];
                            $condArray[] = $newData[30];
                            $condArray[] = $newData[31];
                            // $condArray[] = $newData[32];
                            $condArray[] = $newData[33];
                            $condArray[] = $newData[34];
                            $condArray[] = $newData[35];
                            $condArray[] = $newData[36];
                            $condArray[] = $newData[37];
                        }
                        $finalArray[] = $condArray;
                    }
                }
                $finalArray[] = $newArray;
            }

            
        }

        $itemCount = count($finalArray);
        $itemData = array();
        foreach ($finalArray as $item) {
                $itemData = [];
                $itemData[] = $item[0];
                $itemData[] = $item[1];
                $itemData[] = $item[2];
                $itemData[] = $item[3];
                $itemData[] = $item[4];
                $itemData[] = $item[5];
                $itemData[] = $item[6];
                $itemData[] = $item[7];
                $itemData[] = $item[8];
                $itemData[] = $item[9];
                $itemData[] = $item[10];
                $itemData[] = $item[11];
                $itemData[] = $item[12];
                $itemData[] = $item[13];
                // $itemData[] = $item[14];
                // $itemData[] = $item[15];
                // $itemData[] = $item[16];
                $itemData[] = $item[17];
                // $itemData[] = $item[18];
                // $itemData[] = $item[19];
                // $itemData[] = $item[20];
                $itemData[] = $item[21];
                $itemData[] = $item[22];
                $itemData[] = $item[23];
                $itemData[] = $item[24];
                $itemData[] = $item[25];
                $itemData[] = $item[26];
                $itemData[] = $item[27];
                $itemData[] = $item[28];
                $itemData[] = $item[29];
                $itemData[] = $item[30];
                $itemData[] = $item[31];
                $itemData[] = $item[32];
                $itemData[] = $item[33];
                $itemData[] = $item[34];
                $itemData[] = $item[35];
                // $itemData[] = $item[36];
                // $itemData[] = $item[37];
            
            $stream->writeCsv($itemData); 
        // }

        }
    // echo "<pre>";print_r($itemData);die;
        $content = [];
        $content['type'] = 'filename'; // must keep filename
        $content['value'] = $filepath;
        $content['rm'] = '1'; //remove csv from var folder
 
        $csvfilename = 'Product.csv';
        return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);
     // die();

        $data1 = $this->getRequest()->getPostValue();
        // echo "<pre>";print_r($data1);die;
        $nm=$data1["name"];
        $date=date("Y-m-d");              

        $useremail = $this->_adminSession->getUser()->getEmail();
        $username = $this->_adminSession->getUser()->getFirstname();
        if($username == $nm)  
        {    $username = $this->_adminSession->getUser()->getFirstname(); }
        else
         {   $username = $nm; }


      
        $array2 = array("user" => $useremail, "name" => $username,"createat" => $date);
        $data = array_merge($data1, $array2);
        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('Emipro\Blog\Model\Grid');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);      

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('blog/*/edit',['id' => $model->getId(), '_current' => true]);
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

    public function getColumnHeader() {
        $headers = ['Id','Product name','SKU','Price'];
        return $headers;
    }
}