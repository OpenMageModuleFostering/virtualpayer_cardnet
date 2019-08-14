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
class Virtualpayer_Cardnet_Block_Redirect_Redirect extends Mage_Core_Block_Abstract
{
    /**
     * @return string
     */
    protected function _toHtml()
    {
        $currency_map = array (
			'ADP' => 20,
			'AED' => 784,
			'AFN' => 971,
			'ALL' => 8,
			'AMD' => 51,
			'ANG' => 532,
			'AOA' => 973,
			'ARS' => 32,
			'ATS' => 40,
			'AUD' => 36,
			'AWG' => 533,
			'AZN' => 31,
			'BAM' => 977,
			'BBD' => 52,
			'BDT' => 50,
			'BEF' => 56,
			'BGL' => 100,
			'BGN' => 975,
			'BHD' => 48,
			'BIF' => 108,
			'BMD' => 60,
			'BND' => 96,
			'BOB' => 68,
			'BOV' => 984,
			'BRL' => 986,
			'BSD' => 44,
			'BTN' => 64,
			'BWP' => 72,
			'BYR' => 974,
			'BZD' => 84,
			'CAD' => 124,
			'CDF' => 976,
			'CHF' => 756,
			'CLF' => 990,
			'CLP' => 152,
			'CNY' => 156,
			'COP' => 170,
			'CRC' => 188,
			'CSD' => 891,
			'CUP' => 192,
			'CVE' => 132,
			'CZK' => 203,
			'DEM' => 280,
			'DJF' => 262,
			'DKK' => 208,
			'DOP' => 214,
			'DZD' => 12,
			'EEK' => 233,
			'EGP' => 818,
			'ERN' => 232,
			'ESP' => 724,
			'ETB' => 230,
			'EUR' => 978,
			'FIM' => 246,
			'FJD' => 242,
			'FKP' => 238,
			'FRF' => 250,
			'GBP' => 826,
			'GEL' => 981,
			'GHC' => 288,
			'GIP' => 292,
			'GMD' => 270,
			'GNF' => 324,
			'GRD' => 300,
			'GTQ' => 320,
			'GWP' => 624,
			'GYD' => 328,
			'HKD' => 344,
			'HNL' => 340,
			'HRK' => 191,
			'HTG' => 332,
			'HUF' => 348,
			'IDR' => 360,
			'IEP' => 372,
			'ILS' => 376,
			'INR' => 356,
			'IQD' => 368,
			'IRR' => 364,
			'ISK' => 352,
			'ITL' => 380,
			'JMD' => 388,
			'JOD' => 400,
			'JPY' => 392,
			'KES' => 404,
			'KGS' => 417,
			'KHR' => 116,
			'KMF' => 174,
			'KPW' => 408,
			'KRW' => 410,
			'KWD' => 414,
			'KYD' => 136,
			'KZT' => 398,
			'LAK' => 418,
			'LBP' => 422,
			'LKR' => 144,
			'LRD' => 430,
			'LTL' => 440,
			'LUF' => 442,
			'LVL' => 428,
			'LYD' => 434,
			'MAD' => 504,
			'MDL' => 498,
			'MGF' => 450,
			'MKD' => 807,
			'MMK' => 104,
			'MNT' => 496,
			'MOP' => 446,
			'MRO' => 478,
			'MTL' => 470,
			'MUR' => 480,
			'MVR' => 462,
			'MWK' => 454,
			'MXN' => 484,
			'MXV' => 979,
			'MYR' => 458,
			'MZM' => 508,
			'NAD' => 516,
			'NGN' => 566,
			'NIO' => 558,
			'NLG' => 528,
			'NOK' => 578,
			'NPR' => 524,
			'NZD' => 554,
			'OMR' => 512,
			'PAB' => 590,
			'PEN' => 604,
			'PGK' => 598,
			'PHP' => 608,
			'PKR' => 586,
			'PLN' => 985,
			'PTE' => 620,
			'PYG' => 600,
			'QAR' => 634,
			'ROL' => 642,
			'RUB' => 643,
			'RUR' => 810,
			'RWF' => 646,
			'SAR' => 682,
			'SBD' => 90,
			'SCR' => 690,
			'SDD' => 736,
			'SDG' => 938,
			'SEK' => 752,
			'SGD' => 702,
			'SHP' => 654,
			'SIT' => 705,
			'SKK' => 703,
			'SLL' => 694,
			'SOS' => 706,
			'SRG' => 740,
			'STD' => 678,
			'SVC' => 222,
			'SYP' => 760,
			'SZL' => 748,
			'THB' => 764,
			'TJS' => 972,
			'TMM' => 795,
			'TND' => 788,
			'TOP' => 776,
			'TRY' => 949,
			'TTD' => 780,
			'TWD' => 901,
			'TZS' => 834,
			'UAH' => 980,
			'UGX' => 800,
			'USD' => 840,
			'USN' => 997,
			'USS' => 998,
			'UYU' => 858,
			'UZS' => 860,
			'VEB' => 862,
			'VEF' => 937,
			'VND' => 704,
			'VUV' => 548,
			'WST' => 882,
			'XAF' => 950,
			'XCD' => 951,
			'XOF' => 952,
			'XPF' => 953,
			'YER' => 886,
			'ZAR' => 710,
			'ZMK' => 894,
			'ZWD' => 716
		);
		
		$timestamp = $orderid = $currency = $sha1hash = '';
        
		
		$redirect = Mage::getModel('cardnet/redirect');
        
		$dateTime = date("Y:m:d-H:i:s");
        $timestamp = strftime("%Y%m%d%H%M%S");

		$auction= $redirect->getCardnetUrl();
		if($redirect->getConfigData('test'))
			$auction = $redirect->getCardnetTestUrl();
			
        $form = new Varien_Data_Form();
        $form->setAction($auction)
            ->setId('cardnet_redirect_checkout')
            ->setName('cardnet_redirect_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);


        $orderid = $redirect->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($orderid);
        		
		if($redirect->getConfigData('currency') == 'display'){
	        $currency = $redirect->getQuote()->getStore()->getCurrentCurrency();
            $amount = $order->getGrandTotal();
		}else{
			$currency = $order->getBaseCurrencyCode();
			$amount = $order->getBaseGrandTotal();
		}
		$currency=$currency_map[$currency];		
		        
        $storeid = $redirect->getConfigData('storeid');
        $secret = $redirect->getConfigData('sharedsecret');
        
        
      	$stringToHash = $storeid . $dateTime . $amount . $currency . $secret;
		$ascii = bin2hex($stringToHash);
		$sha1hash = sha1($ascii);
		
		
		$form->addField('txntype', 'hidden', array('name'=>'txntype', 'value'=>'sale'));
        $form->addField('oid', 'hidden', array('name'=>'oid', 'value'=>$orderid));
        $form->addField('timezone', 'hidden', array('name'=>'timezone', 'value'=>date('T')));
        $form->addField('txndatetime', 'hidden', array('name'=>'txndatetime', 'value'=>$dateTime));
        $form->addField('hash', 'hidden', array('name'=>'hash', 'value'=>$sha1hash));
        $form->addField('storename', 'hidden', array('name'=>'storename', 'value'=>$storeid));
        $form->addField('mode', 'hidden', array('name'=>'mode', 'value'=>'payonly'));
        $form->addField('responseSuccessURL', 'hidden', array('name'=>'responseSuccessURL', 'value'=>$redirect->getSuccessUrl()));
        $form->addField('responseFailURL', 'hidden', array('name'=>'responseFailURL', 'value'=>$redirect->getCancelUrl()));
        $form->addField('chargetotal', 'hidden', array('name'=>'chargetotal', 'value'=>$amount));
        $form->addField('currency', 'hidden', array('name'=>'currency', 'value'=>$currency));

        $html = '<html><body>';
        $html.= Mage::app()->getLayout()->createBlock('cms/block')->setBlockId('cardnet_redirect')->toHtml();
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("cardnet_redirect_checkout").submit();</script>';
        $html.= '</body></html>';
		
		
		
        
        if($redirect->getConfigData('debug')){
	        Mage::log($html);
        }

        return $html;
    }
}