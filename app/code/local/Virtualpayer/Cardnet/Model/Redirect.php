<?php
/**
 * Virtualpayer_Cardnet Connect Plugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Virtualpayer
 * @package    Virtualpayer_Cardnet
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtualpayer_Cardnet_Model_Redirect extends Mage_Payment_Model_Method_Abstract{

    protected $_code  = 'cardnet';
    protected $_formBlockType = 'cardnet/redirect_form';
    protected $_allowCurrencyCode = array('AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'JPY', 'NOK', 'NZD', 'PLN', 'SEK', 'SGD','USD');

    /**
     * @param $data
     * @return Virtualpayer_Cardnet_Model_Redirect
     */
 	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCcType($data->getAmex());
                
        return $this;
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current quote
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * Using internal pages for input payment data
     *
     * @return bool
     */
    public function canUseInternal()
    {
        return false;
    }

    /**
     * Using for multiple shipping address
     *
     * @return bool
     */
    public function canUseForMultishipping()
    {
        return false;
    }

    public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('cardnet/redirect_form', $name)
            ->setMethod('cardnet_redirect')
            ->setPayment($this->getPayment())
            ->setTemplate('cardnet/redirect/form.phtml');

        return $block;
    }

    /**
     *
     * @return Virtualpayer_Cardnet_Model_Redirect
     */

    public function validate()
    {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!in_array($currency_code,$this->_allowCurrencyCode)) {
            }
        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Virtualpayer_Cardnet_Model_Redirect
     */
    public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment)
    {
       return $this;
    }

    /**
     * @param Mage_Sales_Model_Invoice_Payment $payment
     * @return void
     */
    public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment)
    {

    }

    /**
     * @return bool
     */
    public function canCapture()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
          return Mage::getUrl('cardnet/redirect/', array('_secure' => true));
    }

    
    /**
     * @return string
     */
    public function getSuccessUrl(){
    	return Mage::getUrl('cardnet/redirect/success');
    }

    /**
     * @return string
     */
    public function getCancelUrl(){
    	return Mage::getUrl('cardnet/redirect/cancel');
    }

    /**
     * @return string
     */
    public function getCardnetUrl(){
		$url = "https://www.ipg-online.com/connect/gateway/processing";
        return $url;
    }

    public function getCardnetTestUrl(){
		$url = "https://test.ipg-online.com/connect/gateway/processing";
        return $url;
    }

     /**
     * @return bool
     */
    public function isInitializeNeeded()
    {
        return true;
    }

    /**
     * @param $paymentAction
     * @param $stateObject
     * @return void
     */
    public function initialize($paymentAction, $stateObject)
    {
        $state = "Cardnet Processing";
        $stateObject->setState($state);
        $stateObject->setIsNotified(false);
    }

    /**
     * @return bool
     */
    public function processRedirectResponse($post){
    	Mage::log($post);    
    	$this->saveCardnetTransaction($post);
    	
        $timestamp = $post['tdate'];
		$result = $post['processor_response_code'];
		$orderid = $post['oid'];
		$message = $post['status'];
		$authcode = $post['terminal_id'];
		$pasref = $post['refnumber'];
		$cardnetsha1 = $post['response_hash'];

        $redirect = Mage::getModel('cardnet/redirect');
        $storeid = $redirect->getConfigData('login');
        $secret = $redirect->getConfigData('pwd');

		$tmp = "$timestamp.$storeid.$orderid.$result.$message.$pasref.$authcode";
		$sha1hash = sha1($tmp);
		$tmp = "$sha1hash.$secret";
		$sha1hash = sha1($tmp);
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderid);

		$session = Mage::getSingleton('checkout/session');
		$session->setOrderId($orderid);

		if ($result != "00" && $message != 'APPROVED') {
			if ($order->getId()) {
				$order->cancel();
				$order->addStatusToHistory('cancelled', $post['fail_reason'], false);
				$order->save();
			}
			return false;
		}else{
			if ($result == "00" || $message == 'APPROVED') {
				if ($order->getId()) {
					$order->addStatusToHistory('processing', 'Payment Successful: ' . $result . ': ' . $message, false);
					$order->addStatusToHistory('processing', 'Authorisation Code: ' . $authcode, false);
					$order->sendNewOrderEmail();
					$order->setEmailSent(true);

					$session->setLastSuccessQuoteId($order->getId());
					$session->setLastQuoteId($order->getId());
			        $session->setLastOrderId($order->getId());

					$order->save();
				}
		        if($redirect->getConfigData('capture')){
					Mage::helper('cardnet')->createInvoice($orderid);
				}
				return true;
			}else{
				$session->addError('There was a problem completing your order. Please try again');
				if ($order->getId()) {
					$order->addStatusToHistory('cancelled', $result . ': ' . $message, false);
					$order->cancel();
				}
				$order->save();
				return false;
	        }
	    }
    }
    
    public function saveCardnetTransaction($post){
        $cardnet = Mage::getModel('cardnet/cardnet');

		try{
	        $cardnet->setOrderId($post['oid'])
                    ->setTimestamp(Mage::helper('cardnet')->getDateFromTimestamp($post['txndatetime']))
                    ->setTerminalid($post['terminal_id'])
                    ->setExpmonth($post['expmonth'])
                    ->setStatus($post['status'])
                    ->setFailreason($post['fail_reason'])
                    ->setOid($post['oid'])
                    ->setCurrency($post['currency'])
                    ->setProcessorresponsecode($post['processor_response_code'])
                    ->setApprovalcode($post['approval_code'])
                    ->setExpyear($post['expyear'])
                    ->setRefnumber($post['refnumber'])
                    ->setCcbrand($post['ccbrand'])
                    ->setCccountry($post['cccountry'])
                    ->setTimezone($post['timezone'])
                    ->setChargetotal($post['chargetotal'])
                    ->setTxntype($post['txntype'])
                    ->setCcbin($post['ccbin'])
                    ->setTdate($post['tdate'])
                    ->setTxndate_processed($post['txndate_processed'])
                    ->setFailrc($post['fail_rc'])
                    ->setResponsehash($post['response_hash'])
                    ->setPaymentMethod($post['paymentMethod'])
                    ->save();
        }catch(Exception $e){
    		Mage::logException($e);
    	}
    }
}

?>
