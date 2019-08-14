<?php
/**
 * VirtualPayer_Cardnet Connect Plugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   VirtualPayer
 * @package    VirtualPayer_Cardnet
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class VirtualPayer_Cardnet_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @param $orderIncrementId
     * @return string
     */
    public function createInvoice(string $orderIncrementId){
  		$_order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

		if($_order->canInvoice()) {
			$invoiceId = Mage::getModel('sales/order_invoice_api')->create($_order->getIncrementId(), array(), 'Invoice Created', true, true);

			$invoice = Mage::getModel('sales/order_invoice_api')->capture($invoiceId);
		}
    }

    public function getDateFromTimestamp($timestamp){
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minutes = substr($timestamp, 10, 2);
        $seconds = substr($timestamp, 12, 2);
        $date = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minutes . ':' . $seconds;
        Mage::log($date);
        return strtotime($date);
    }
    
    public function getCustomerMessage($block, $order, $response){
    	$variables = array();
    	$variables['order'] = $order;
    	$variables['response'] = $response;
    	$filter = Mage::getModel('core/email_template_filter');
    	$filter->setVariables($variables); 
    	return $filter->filter($block->toHtml());
    }
}


