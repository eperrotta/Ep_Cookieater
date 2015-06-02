<?php


class Ep_Cookieater_CookieaterController extends Mage_Core_Controller_Front_Action {

	public function setCookieAction() {

		$cookiesParams = $this->getRequest()->getParams();

		$cookieLevel = 'accept-tech';

		if(isset($cookiesParams['accept-prof'])) {
			$cookieLevel = $cookiesParams['accept-prof'];
		}

		if(isset($cookiesParams['accept-third'])) {
			$cookieLevel = $cookiesParams['accept-third'];
		}

		if(isset($cookiesParams['accept-all'])) {
			$cookieLevel = 'accept-all';
		}

		if(isset($cookiesParams['accept-prof']) && isset($cookiesParams['accept-third'])) {
			$cookieLevel = 'accept-all';
		}

		Mage::getModel('core/cookie')->set('ep_cookieater', $cookieLevel, (86400 * 365));

		$redirectUrl = $this->_getRefererUrl();
		if (empty($redirectUrl))
			$redirectUrl = Mage::getBaseUrl();

		$this->getResponse()->setRedirect($redirectUrl);
		return $this;
	}
}