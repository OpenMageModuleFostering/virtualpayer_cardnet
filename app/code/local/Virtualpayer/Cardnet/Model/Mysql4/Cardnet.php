<?php

class Virtualpayer_Cardnet_Model_Mysql4_Cardnet extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('cardnet/cardnet', 'cardnet_id');
    }
}