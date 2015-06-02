<?php

class Ep_Cookieater_Block_Form extends Mage_Core_Block_Template {

	public function _prepareLayout() {
		return parent::_prepareLayout();
	}

	public function getIsChecked($compare) {
		if(isset($_COOKIE['ep_cookieater'])) {
			if(($_COOKIE['ep_cookieater'] == 'accept-all') || ($_COOKIE['ep_cookieater'] == $compare))
				return true;
		}
		return false;
	}

	public function isActionRequired() {
		return true;
		return !isset($_COOKIE['ep_cookieater']);
	}
}