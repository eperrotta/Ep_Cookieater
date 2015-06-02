<?php

class Ep_Cookieater_Model_Admin_System_Config_Source_Page {

	protected $_options;

	public function toOptionArray() {
		if (!$this->_options) {
			$this->_options = Mage::getResourceModel('cms/page_collection')
				->load()->toOptionIdArray();
		}
		return $this->_options;
	}

}
