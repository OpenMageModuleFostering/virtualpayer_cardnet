<?php
class Virtualpayer_Cardnet_Block_Adminhtml_Cardnet_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('cardnetGrid');
      $this->setDefaultSort('timestamp');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('cardnet/cardnet')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  
     /**
     * Return Current work store
     *
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        return Mage::app()->getStore();
    }

  protected function _prepareColumns()
  {

     $this->addColumn('order_id', array(
          'header'    => Mage::helper('cardnet')->__('Order ID'),
          'index'     => 'order_id',
     ));

      $this->addColumn('timestamp', array(
          'header'    => Mage::helper('cardnet')->__('Timestamp'),
          'type'        => 'datetime',
          'index'     => 'timestamp',
     ));


      $this->addColumn('oid', array(
          'header'    => Mage::helper('cardnet')->__('cardnet Order ID'),
          'index'     => 'oid',
     ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('cardnet')->__('Status'),
          'index'     => 'status',
          'width'     => '50px',

     ));

      $this->addColumn('fail_reason', array(
          'header'    => Mage::helper('cardnet')->__('Fail Reason'),
          'index'     => 'fail_reason',
     ));

      $this->addColumn('cardnumber', array(
          'header'    => Mage::helper('cardnet')->__('Card Number'),
          'index'     => 'cardnumber',
     ));

      $this->addColumn('currency', array(
          'header'    => Mage::helper('cardnet')->__('Currency'),
          'index'     => 'currency',
          'width'     => '50px',
     ));

      $this->addColumn('refnumber', array(
          'header'    => Mage::helper('cardnet')->__('Referance Number'),
          'index'     => 'refnumber',
     ));

      $this->addColumn('chargetotal', array(
          'header'    => Mage::helper('cardnet')->__('Amount'),
          'index'     => 'chargetotal',
     ));

      $this->addColumn('paymentMethod', array(
          'header'    => Mage::helper('cardnet')->__('Payment Method'),
          'index'     => 'paymentMethod',
     ));

      $this->addColumn('processor_response_code', array(
          'header'    => Mage::helper('cardnet')->__('Response Code'),
          'index'     => 'processor_response_code',
     ));

    
	
		$this->addExportType('*/*/exportCsv', Mage::helper('cardnet')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('cardnet')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('cardnet_id');
        $this->getMassactionBlock()->setFormFieldName('cardnet');
        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}