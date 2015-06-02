<?php

class Ep_Cookieater_Block_Popup extends Mage_Core_Block_Template {

	public function _prepareLayout() {
		return parent::_prepareLayout();
	}

	public function getPopupContent() {

		$popupBlockId = Mage::getStoreConfig('cookieater_options/cookieater_configuration/popup_block');
		error_log($popupBlockId);
		$popupBlock = Mage::getBlockSingleton('cms/block')->setBlockId($popupBlockId)->toHtml();
		return $popupBlock;
	}

	public function isActionRequired() {
		return (!isset($_COOKIE['ep_cookieater']) || (
			$_COOKIE['ep_cookieater'] != 'accept-tech' &&
			$_COOKIE['ep_cookieater'] != 'accept-prof' &&
			$_COOKIE['ep_cookieater'] != 'accept-third' &&
			$_COOKIE['ep_cookieater'] != 'accept-all'
			));
	}

	public function getPolicyLink() {
		$popupBlockId = Mage::getStoreConfig('cookieater_options/cookieater_configuration/policy_page');
	}
}