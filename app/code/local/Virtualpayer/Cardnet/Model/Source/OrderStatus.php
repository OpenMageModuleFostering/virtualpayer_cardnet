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
class Virtualpayer_Cardnet_Model_Source_OrderStatus{
	
    public function toOptionArray()
    {
        return array(
	        array('value' => 'canceled', 'label' => Mage::helper('cardnet')->__('Canceled')),
            array('value' => 'pending', 'label' => Mage::helper('cardnet')->__('Pending')),
            array('value' => 'processing', 'label' => Mage::helper('cardnet')->__('Processing')),
        );
    }
}