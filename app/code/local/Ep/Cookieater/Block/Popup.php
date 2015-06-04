<?php
/**
 * Ep Cookieater Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Ep
 * @package	Ep_Cookieater
 * @copyright	Copyright (c) 2015 Enzo Perrotta (http://www.enzoperrotta.it)
 * @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @category   Cookies Blocker
 * @package    Ep_Cookieater
 * @author     Enzo Perrotta
 * @property   Enzo Perrotta
 * @copyright  Copyright (c) 2015 Enzo Perrotta (http://www.enzoperrotta.it)
 */

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
		$link = explode('|', Mage::getStoreConfig('cookieater_options/cookieater_configuration/policy_page'));
		$popupBlockId = $link[0];
		return $popupBlockId;
	}
}