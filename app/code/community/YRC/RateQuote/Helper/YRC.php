<?php

/**
 * YRC Rate Quote Helper
 *
 * @category YRC
 * @package YRC_RateQuote
 * @author Justin Page | KLVTZ
 */

class YRC_RateQuote_Helper_YRC extends Mage_Core_Helper_Abstract
{
	/**
	 * Get Rate Quote from YRC API
	 *
	 * @param String $base_url
	 * @param Integer $total_weight
	 * @param Array $freight_classes
	 *
	 * @return String $xml_data
	 */
	public function getRateQuote($base_url = '')
	{
		return $this->requestXML($base_url);
	}

	/**
	 * Request XML Data from YRC API
	 *
	 * @param String $info
	 * @return String $xml
	 */
	protected function requestXML($base_url = '')
	{
		$file_contents = file_get_contents($base_url);
		return $this->cleanXML($file_contents);
	}

	/**
	 * Clean XML Requested data and return string conversion
	 *
	 * @param XML $data
	 * @return String
	 */
	protected function cleanXML($data)
	{
		$data = str_replace(array("\n", "\r", "\t"), '', $data);
		return simplexml_load_string($data);
	}
}
