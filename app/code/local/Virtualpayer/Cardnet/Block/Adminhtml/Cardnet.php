<?php
class Virtualpayer_Cardnet_Block_Adminhtml_Cardnet extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_cardnet';
    $this->_blockGroup = 'cardnet';
    $this->_headerText = Mage::helper('cardnet')->__('Cardnet Transactions');
    parent::__construct();
    $this->_removeButton('add');
  }
}