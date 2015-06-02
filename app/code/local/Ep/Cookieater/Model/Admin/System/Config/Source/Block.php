<?php

class Ep_Cookieater_Model_Admin_System_Config_Source_Block {

	protected $_options;

	public function toOptionArray() {
		if (!$this->_options) {
			$this->_options = Mage::getResourceModel('cms/block_collection')
				->load()->toOptionArray();
		}
		return $this->_options;
	}

}
