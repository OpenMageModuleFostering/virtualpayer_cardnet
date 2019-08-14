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
class VirtualPayer_Cardnet_Model_Source_Currency
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'base',
                'label' => Mage::helper('cardnet')->__('Use Base Currency')
            ),
            array(
                'value' => 'display',
                'label' => Mage::helper('cardnet')->__('Use Display Currency')
            ),
        );
    }
}

?>
