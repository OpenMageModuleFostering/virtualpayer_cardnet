<?php

class VirtualPayer_Cardnet_Adminhtml_CardnetController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('cardnet/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Cardnet Transactions Manager'), Mage::helper('adminhtml')->__('Cardnet Connect Transactions Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('cardnet/cardnet')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('cardnet_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cardnet/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Cardnet Manager'), Mage::helper('adminhtml')->__('Cardnet Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Code News'), Mage::helper('adminhtml')->__('Code News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('cardnet/adminhtml_cardnet_edit'))
				->_addLeft($this->getLayout()->createBlock('cardnet/adminhtml_cardnet_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cardnet')->__('Code does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
	
	public function uploadAction() {
			$this->loadLayout();
			$this->_setActiveMenu('cardnet/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Cardnet Manager'), Mage::helper('adminhtml')->__('Cardnet Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Code Import'), Mage::helper('adminhtml')->__('Code Import'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('cardnet/adminhtml_cardnet_import'))
				->_addLeft($this->getLayout()->createBlock('cardnet/adminhtml_cardnet_import_tabs'));

			$this->renderLayout();
	}
	
	public function importCSV(){
		try {	
			$uploader = new Varien_File_Uploader('filename');
       		$uploader->setAllowedExtensions(array('csv'));
			$uploader->setAllowRenameFiles(false);
			$uploader->setFilesDispersion(false);
					
			$path = Mage::getBaseDir('media') . DS ;
			$uploader->save($path, $_FILES['filename']['name'] );

			$row = 1;
			if (($handle = fopen($path . $_FILES['filename']['name'], "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			    	if($row == 1){
				        $num = count($data);				        
				        $headings = array();
				        for ($c=0; $c < $num; $c++) {
							$headings[$c] = $data[$c];
						}
				    	$row++;
				    	continue;
			    	}else{
    					$model = Mage::getModel('cardnet/cardnet');
				        $row++;
				        $num = count($data);				        
				        for ($c=0; $c < $num; $c++) {
		            		$model->setData($headings[$c], $data[$c]);
		            	}
						$model->save();
				    }
			    }
			    fclose($handle);
			}else{
		        Mage::throwException('File Not Found');
			}
			
		} catch (Exception $e) {
      		Mage::throwException($e->getMessage());
        }
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				$this->importCSV();
				$this->_redirect('*/*/');
				return;
			}
	  			
			$model = Mage::getModel('cardnet/cardnet');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cardnet')->__('Code was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cardnet')->__('Unable to find code to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('cardnet/cardnet');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Code was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $cardnetconnectIds = $this->getRequest()->getParam('cardnet');
        if(!is_array($cardnetIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select code(s)'));
        } else {
            try {
                foreach ($cardnetconnectIds as $cardnetconnectId) {
                    $cardnet = Mage::getModel('cardnet/cardnet')->load($cardnetconnectId);
                    $cardnet->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d code(s) were successfully deleted', count($cardnetIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $cardnetconnectIds = $this->getRequest()->getParam('cardnet');
        if(!is_array($cardnetconnectIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select code(s)'));
        } else {
            try {
                foreach ($cardnetconnectIds as $cardnetconnectId) {
                    $cardnet = Mage::getSingleton('cardnet/cardnet')
                        ->load($cardnetconnectId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d code(s) were successfully updated', count($cardnetconnectIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function massAssignAction(){
    	$cardnetconnectIds = $this->getRequest()->getParam('cardnet');
        if(!is_array($fdmsconnectIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select code(s)'));
        } else {
            try {
                foreach ($cardnetconnectIds as $cardnetconnectId) {
                    $cardnet = Mage::getSingleton('cardnet/cardnet')
                        ->load($cardnetconnectId)
                        ->setAgentId($this->getRequest()->getParam('agent_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d code(s) were successfully updated', count($cardnetconnectIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'cardnet.csv';
        $content    = $this->getLayout()->createBlock('cardnet/adminhtml_cardnet_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'cardnet.xml';
        $content    = $this->getLayout()->createBlock('cardnet/adminhtml_cardnet_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}