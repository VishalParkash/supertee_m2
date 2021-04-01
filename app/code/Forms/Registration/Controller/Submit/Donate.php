<?php
namespace Forms\Registration\Controller\Submit;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\JsonFactory;


class Donate extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $customer;
    protected $resource;
    protected $connection;


     public function __construct(\Magento\Framework\App\Action\Context $context,
        ResourceConnection $resource,
        \Magento\Customer\Model\Session $customer,
        JsonFactory $resultJsonFactory){
        parent::__construct($context);

        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();
        $this->customer = $customer;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute(){

        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {
            $resultJson = $this->resultJsonFactory->create();
            
            try {
                $customer = $this->customer;
                $customerId = $customer->getId();
                $UserEmail = $customer->getCustomer()->getEmail();
                $UserName = $customer->getCustomer()->getName();
                $organisation_name = $post['organisation_name'];
                $contact_person = $post['contact_person'];
                $donation_points = $post['donation_points'];

                $donation_amt = $donation_points*(0.02);
                // $donation_amt = $post['donation_amt'];
                $org_email = $post['org_email'];
                $org_phone = $post['org_phone'];
                $org_address = $post['org_address'];
                $donation_notes = $post['donation_notes'];

                $themeTable = $this->connection->getTableName('superTee_donations');

                // $checkReferral = "SELECT * FROM " . $themeTable . " WHERE donated_to ='".$organisation_name."' AND donated_by = '".$customerId."'";
                // $result = $this->connection->fetchAll($checkReferral);

                $sql = "INSERT INTO " . $themeTable . "(donated_by, donated_to, donation_points, donation_amt, contact_person, org_email, org_phone, org_address, donation_notes) VALUES ('".$customerId."', '".$organisation_name."','".$donation_points."', '".$donation_amt."','".$contact_person."', '".$org_email."','".$org_phone."','".$org_address."','".$donation_notes."' )";
                    if($this->connection->query($sql)){
                        $response = true;
                    }else{
                       $response = false; 
                    }

                        if($response == true){
                            $themeTable2 = $this->connection->getTableName('user_rewards');
                         $sql = "INSERT INTO " . $themeTable2 . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customerId.", ".$donation_points.", 'debit',  'forDonation')";
                            if($this->connection->query($sql)){
                                $response = true;

                                $adminEmail = "supertee.admin@mailinator.com";
                                $emailForAdmin = new \Zend_Mail();
                                $emailForOrg = new \Zend_Mail();
                                // die;
                                $msg = '';
                                $msg .= "<p>Hi Admin</p>";
                                $msg .= "<p>".ucwords($UserName)." has donated funds for the organisation.".$organisation_name." </p>";
                                $msg .= "<p>Regards</p>";
                                $msg .= "<p>Team Supertee</p>";
                                
                                $emailForAdmin->setSubject("Points donated to organisation");
                                $emailForAdmin->setBodyHtml($msg);
                                $emailForAdmin->setFrom($UserEmail, $UserName);
                                $emailForAdmin->addTo($adminEmail, "Supertee");
                                if($emailForAdmin->send()){
                                    $msg2 = '';
                                    $msg2 .= "<p>Hi ".ucwords($contact_person)."</p>";
                                    $msg2 .= "<p>".ucwords($UserName)." from Supertee has donated funds for your organisation.".$organisation_name." </p>";
                                    $msg2 .= "<p>Regards</p>";
                                    $msg2 .= "<p>Team Supertee</p>";
                                    
                                    $emailForOrg->setSubject("Donation from Supertee");
                                    $emailForOrg->setBodyHtml($msg2);
                                    $emailForOrg->setFrom($UserEmail, $UserName);
                                    $emailForOrg->addTo($org_email, $organisation_name);
                                    $emailForOrg->send();

                                    $response = true;

                                }else{
                                    $response = false;
                                }

                            }else{
                                $response = false;
                            }
                        }   
                                
            } catch (Exception $e) {
                // \Zend_Debug::dump($e->getMessage());
                $response = false;

            }
            return $resultJson->setData($response);
        }
    }
}