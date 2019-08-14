<?php

class Virtualpayer_Cardnet_Model_Setup extends Mage_Eav_Model_Entity_Setup
{

	public function createStaticBlocks(){
		$error = Mage::getModel('cms/block');
		$error->setTitle('Cardnet Error Message')
				->setIdentifier('cardnet_error')
				->setContent('{{var response.message}}')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
						
		$success = Mage::getModel('cms/block');
		$success->setTitle('Cardnet Success Message')
				->setIdentifier('cardnet_success')
				->setContent('{{var response.message}}')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
		
		$redirect = Mage::getModel('cms/block');
		$redirect->setTitle('Cardnet Redirect Message')
				->setIdentifier('cardnet_redirect')
				->setContent('You will be redirected to Lloyds Bank Online Payments in a few seconds.')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
	}

}