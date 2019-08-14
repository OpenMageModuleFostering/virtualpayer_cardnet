<?php

class Virtualpayer_Cardnet_Model_Mysql4_Cardnet_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('cardnet/cardnet');
    }
}