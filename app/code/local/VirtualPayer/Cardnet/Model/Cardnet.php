<?php

class VirtualPayer_Cardnet_Model_Cardnet extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cardnet/cardnet');
    }    
}