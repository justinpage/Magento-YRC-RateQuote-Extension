<?php

/**
 * YRC Rate Quote Helper: XML -> JSON
 *
 * @category YRC
 * @package YRC_RateQuote
 * @author Justin Page | KLVTZ
 */
class YRC_RateQuote_Helper_JSON extends Mage_Core_Helper_Abstract
{
	/**
	 * Get Shipping Rate from XML via JSON
	 *
	 * @param String $xml
	 * @return Integer $rate
	 */
	public function getShippingRate($xml)
	{
		return $this->cite($this->convert($xml));
	}

	/**
	 * Get Rate Quote from stdClass
	 *
	 * @param stdClass $rateObj
	 * @return Integer $rate
	 */
	protected function cite($rateObj)
	{
		return (int) $rateObj->BodyMain->RateQuote->RatedCharges->TotalCharges;
	}

	/**
	 * Convert XML to JSON
	 *
	 * @param String $xml
	 * @return Object $rateObj
	 */
	protected function convert($xml)
	{
		return  $this->json_to_object(json_encode($xml));
	}

	/**
	 * Convert JSON to stdClass
	 *
	 * @param String JSON
	 * @return $rateObj
	 */
	protected function json_to_object($json)
	{
		return json_decode($json);
	}
}
