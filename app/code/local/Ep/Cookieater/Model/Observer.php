<?php

class Ep_Cookieater_Model_Observer extends Mage_Core_Model_Observer {

	public function replaceHtmlForCookies($observer) {

		/**
		 * If we are in the admin area just return
		 */
		if ((Mage::app()->getStore()->isAdmin() || Mage::getDesign()->getArea() == 'adminhtml'))
			return;



		$cookiesAccepted = $_COOKIE['ep_cookieater'];

		/**
		 * Get the blacklisted keyword for custom content filter
		 */

		$blockedScriptKeywords = Mage::getStoreConfig('cookieater_options/cookieater_configuration/script_keywords_blacklist');
		$blockedScriptKeywords = empty($blockedScriptKeywords) ? array() : explode(',', $blockedScriptKeywords);

		$blockedIframeKeywords = Mage::getStoreConfig('cookieater_options/cookieater_configuration/iframe_keywords_blacklist');
		$blockedIframeKeywords = empty($blockedIframeKeywords) ? array() : explode(',', $blockedIframeKeywords);


		/**
		 * Get the block from the observer object and parse it as DOM
		 */
		$transport = $observer->getTransport();
		$blockHtml = $transport->getHtml();

		$offset = 0;
		while (strpos($blockHtml, '<script', $offset) !== false) {
			$blockPosition = strpos($blockHtml, '<script', $offset);
			$closingTagPosition = strpos($blockHtml, '</script>', $blockPosition);
			$scriptBlock = substr($blockHtml, $blockPosition, $closingTagPosition - $blockPosition + 9);

			/**
			 * Check if the block should be replaced due to a forbidden keyword
			 */
			$blockForKeyword = false;
			foreach ($blockedScriptKeywords as $keyword) {
				if (strpos($scriptBlock, $keyword) !== false) {
					$blockForKeyword = true;
					break;
				}
			}

			/**
			 * If the script contains code to generate additional script tag, 99% is a third party
			 * service which will generate cookies. Let's remove this tag it
			 * if the policy has not been accepted
			 */
			if (
				(
					($cookiesAccepted != 'accept-all') &&
					((strpos($scriptBlock, 'document.createElement(\'script\')') !== false) || (strpos($scriptBlock, '.src') !== false))
				)
				||
				($blockForKeyword)

			) {
				/**
				 * Replace the content of the script block with the dummy text
				 */
				$dummyScriptBlock = '<script>/* ' . Mage::helper('cookieater')->__('This content has been disabled because you didn\'t accept to install third parties cookies') . ' */</script>';
				$blockHtml = str_replace($scriptBlock, $dummyScriptBlock, $blockHtml);
				$offset = $blockPosition + strlen($dummyScriptBlock);

			} else {
				$offset = $closingTagPosition;
			}
		}


		/**
		 * Block external content content for the iframes if the cookies policy has not been accepted
		 */
		$offset = 0;
		while (strpos($blockHtml, '<iframe', $offset) !== false) {
			$blockPosition = strpos($blockHtml, '<iframe', $offset);
			$closingTagPosition = strpos($blockHtml, '</iframe>', $blockPosition);
			$iframeBlock = substr($blockHtml, $blockPosition, $closingTagPosition - $blockPosition + 9);

			/**
			 * Check if the block should be replaced due to a forbidden keyword
			 */
			$blockForKeyword = false;
			foreach ($blockedIframeKeywords as $keyword) {
				if (strpos($iframeBlock, $keyword) !== false) {
					$blockForKeyword = true;
					break;
				}
			}

			/**
			 * If the iframe source contains third parties content let's remove this unless
			 * the policy has not been accepted
			 */

			// Extract the value from the src attribute of the iframe
			$srcPosition = strpos($iframeBlock, 'src');
			$closingSrcPosition = strpos($iframeBlock, '"', $srcPosition + 5);
			$srcValue = substr($iframeBlock, $srcPosition + 6, $closingSrcPosition - $srcPosition - 7);

			if ($srcValue) {
				$iframeUrl = parse_url($srcValue);
				$iframeHost = $iframeUrl['host'];
			}


			if (
				(
					($cookiesAccepted != 'accept-all') &&
					($iframeHost != Mage::app()->getFrontController()->getRequest()->getHttpHost())
				) ||
				($blockForKeyword)
			) {
				// Replace the content of the script block with the dummy text
				$dummyScriptBlock = '<div class="cookieater-dummyiframe">' . Mage::helper('cookieater')->__('This content has been disabled because you didn\'t accept to install third parties cookies') . '</div>';
				$blockHtml = str_replace($iframeBlock, $dummyScriptBlock, $blockHtml);
				$offset = $blockPosition + strlen($dummyScriptBlock);

			} else {
				$offset = $closingTagPosition;
			}
		}

		$transport->setHtml($blockHtml);
	}

	public function deleteAllCookies() {
		if(!isset($_COOKIE['ep_cookieater']) ||
			(
				($_COOKIE['ep_cookieater'] != 'accept-tech') &&
				($_COOKIE['ep_cookieater'] != 'accept-prof') &&
				($_COOKIE['ep_cookieater'] != 'accept-third') &&
				($_COOKIE['ep_cookieater'] != 'accept-all')
			)
		) {
			if(!Mage::app()->getStore()->isAdmin() && Mage::getDesign()->getArea() != 'adminhtml') {
				foreach($_COOKIE as $cookieName => $cookieValue) {
					if($cookieName != 'adminhtml') {
						Mage::getModel('core/cookie')->set($cookieName, '', time() - 1000);
						Mage::getModel('core/cookie')->set($cookieName, '', time() - 1000, '/');
					}
				}
			}
		}
	}

}