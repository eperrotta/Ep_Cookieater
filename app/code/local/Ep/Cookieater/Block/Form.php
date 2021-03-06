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