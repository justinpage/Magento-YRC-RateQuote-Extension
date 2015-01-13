<?php

/**
 * YRC Rate Quote Implementation
 *
 * @category YRC
 * @package YRC_RateQuote
 * @author Justin Page | KLVTZ
 */

class YRC_RateQuote_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
	implements Mage_Shipping_Model_Carrier_Interface
{
	/**
	 * Rate Quote carrier code and helpers
	 *
	 */
	protected $_code = 'yrc_ratequote';
	protected $yrc_helper;
	protected $json_helper;

	/**
	 * Grab instance of Helper/YRC.php and Helper/XML.php
	 *
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->yrc_helper = Mage::helper('YRC_RateQuote/YRC');
		$this->json_helper = Mage::helper('YRC_RateQuote/JSON');
	}

	/**
	 * Collect YRC Rates and return result to checkout
	 *
	 * @param Object $request
	 * @return float $result
	 */
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{

		// Get the Rate Result Model from Mage_Core_Shipping
		$result = Mage::getModel('shipping/rate_result');
		$result->append($this->_getYrcShippingRate($request));

		return $result;
	}

	/**
	 * Get YRC Shipping Rate from XML Parsed Data
	 *
	 * @param void
	 * @return float $rate
	 */
	protected function _getYrcShippingRate(Mage_Shipping_Model_Rate_Request $request)
	{
		$rate = Mage::getModel('shipping/rate_result_method');
		$rate = $this->setCarrierTypeAndMethod($rate);

		$url = $this->buildURL($request);
		$xml = $this->yrc_helper->getRateQuote($url);
		$price = $this->json_helper->getShippingRate($xml);

		$rate->setPrice(number_format($price/100, 2, '.', ''));

		return $rate;
	}

	/**
	 * Get User Login ID from /etc/system.xml
	 *
	 * @param void
	 * @return String $login_id
	 */
	protected function getLoginId()
	{
		return $this->getConfigData('login_id');
	}

	/**
	 * Get Login Password from /etc/system.xml
	 *
	 * @param void
	 * @return String $login_password
	 */
	protected function getLoginPassword()
	{
		return
			Mage::helper('core')->decrypt($this->getConfigData('login_password'));
	}

	/**
	 * Get Base URL from /etc/system.xml
	 *
	 * @param void
	 * @return String $base_url
	 */
	protected function getBaseURL()
	{
		return $this->getConfigData('base_url');
	}

	/**
	 * Get Business Identication from /etc/system.xml
	 *
	 * @param void
	 * @return String $bus_id
	 */
	protected function getBusId()
	{
		return $this->getConfigData('bus_id');
	}

	/**
	 * Get Business Role from /etc/system.xml
	 *
	 * @param void
	 * @return String $bus_role
	 */
	protected function getBusRole()
	{
		return $this->getConfigData('bus_role');
	}

	/**
	 * Get Origin City Name from /etc/system.xml
	 *
	 * @param void
	 * @return $origin_city_name
	 */
	protected function getOrigCityName()
	{
		return $this->getConfigData('city');
	}

	/**
	 * Get Origin State Code from  /etc/system.xml
	 *
	 * @param void
	 * @returh $origin_state_code
	 */
	protected function getOrigStateCode()
	{
		return Mage::getSingleton('directory/region')->load($this->getConfigData('region_id'))->getCode();
	}

	/**
	 * Get Origin Zip Code from /etc/system.xml
	 *
	 * @param void
	 *
	 * @return String $origin_zip
	 */
	protected function getOrigZip()
	{
		return $this->getConfigData('postcode');
	}

	/**
	 * Get Origin Nation from /etc/system.xml
	 *
	 * @param void
	 * @return String $origin_nation
	 */
	protected function getOrigNationCode()
	{
		$country = $this->getConfigData('country_id');

		switch ($country) {
		case 'US':
			return 'USA';
		case 'CAN':
			return 'CAN';
		default:
			return  'INVALID_NATION';
		}
	}

	/**
	 * Get Destination City Name
	 *
	 * @param void
	 * @return dest_city_name
	 */
	protected function getDestCityName()
	{
		return
			Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getCity();
	}

	/**
	 * Get Destination State Code
	 *
	 * @param void
	 * @return dest_state_code
	 */
	protected function getDestStateCode()
	{
		$customerSessionRegionid = Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getRegionId();
		return Mage::getSingleton('directory/region')->load($customerSessionRegionid)->getCode();
	}

	/**
	 * Get Destination Zip code from Mage Checkout Session
	 *
	 * @param void
	 * @return String $destZip
	 */
	protected function getDestZip()
	{
		return
			Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getPostcode();
	}

	/**
	 * Get Destination Nation Code: USA or CAN
	 *
	 * @param void
	 * @return String $destNationCode`
	 */
	protected function getDestNationCode()
	{
		$country =
			Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getCountry();

		switch ($country) {
		case 'US':
			return 'USA';
		case 'CAN':
			return 'CAN';
		default:
			return 'Unsupported Nation Code';
		}
	}

	/**
	 * Get Service Class from /etc/system.xml
	 *
	 * @param void
	 * @return String $serviceClass
	 */
	protected function getServiceClass()
	{
		return $this->getConfigData('service_class');
	}

	/**
	 * Get Type Query from /etc/system.xml
	 *
	 * @param void
	 * @return String $typeQuery
	 */
	protected function getTypeQuery()
	{
		return $this->getConfigData('type_query');
	}

	/**
	 * Get Payment Terms from /etc/system.xml
	 *
	 * @param void
	 * @return String $typeQuery
	 */
	protected function getPaymentTerms()
	{
		return $this->getConfigData('payment_terms');
	}

	/**
	 * Get Line item Nmfc Class from /etc/system.xml
	 *
	 * @param void
	 * @return Integer $line_item_Nmfc_class
	 */
	protected function getLineItemNmfcClass()
	{
		return $this->getConfigData('line_item_nmfc_class');
	}

	/**
	 * Get Line Item Count from Mage Checkout Session
	 *
	 * @param void
	 * @return Integer $item_count
	 */
	protected function getLineItemCount()
	{
		return
			Mage::getSingleton('checkout/session')->getQuote()->getItemsCount();
	}

	/**
	 * Get Total Weight from each Products Definition
	 *
	 * @param Object $request
	 * @return  Integer $total_weight
	 */
	protected function getTotalWeight(Mage_Shipping_Model_Rate_Request $request)
	{
		$total_weight = 0;

		foreach ($request->getAllItems() as $_item) {
			$total_weight += round($_item->getRowWeight());
		}

		return $total_weight;
	}

	/**
	 * Get Freight Class from each Product's Definition
	 *
	 * @param Object $request
	 * @return Array $frieght_classes
	 */
	protected function getFreightClass(Mage_Shipping_Model_Rate_Request $request)
	{
		foreach ($request->getAllItems as $_item) {
			$freight_classes[] = $_item->getFreightClass();
		}

		return $freight_classes;
	}


	/**
	 * Get Pickup Date from DateTime Object
	 *
	 * @param void
	 * @return string $pickup_date
	 */
	protected function getPickupDate()
	{
		$date = new DateTime(strftime("%Y-%m-%d", time()));
		$inc  = "P" . $this->getConfigData('pickup_increment') . "D";
		$date->add(new DateInterval($inc));
		return $date->format("Ymd");
	}

	/**
	 * Set Carrier Type, Title, Method, and Method Title
	 *
	 * @param Object $rate
	 * @return Object $rate
	 */
	protected function
		setCarrierTypeAndMethod(Mage_Shipping_Model_Rate_Result_Method $rate)
	{
		$rate->setCarrier($this->_code);
		$rate->setCarrierTitle($this->getConfigData('title'));
		$rate->setMethod('yrc_shipping');
		$rate->setMethodTitle('Freight Cost');

		return $rate;
	}

	/**
	 * Get Full URL: Combine Base URL and Parameter Requests
	 *
	 * @param String Object $request
	 * @return String $url
	 */
	protected function buildURL(Mage_Shipping_Model_Rate_Request $request)
	{
		$url = $this->getBaseURL();

		// Credentials: User ID and Password
		$url .= '&LOGIN_USERID=' . $this->getLoginId();
		$url .= '&LOGIN_PASSWORD=' . $this->getLoginPassword();

		// Playworld Systems Business Information
		$url .= '&BusId=' . $this->getBusId();
		$url .= '&BusRole=' . $this->getBusRole();
		$url .= '&PaymentTerms=' . $this->getPaymentTerms();

		// From: Playworld Systems
		$url .= '&OrigCityName=' . $this->getOrigCityName();
		$url .= '&OrigStateCode=' . $this->getOrigStateCode();
		$url .= '&OrigZipCode=' . $this->getOrigZip();
		$url .= '&OrigNationCode=' . $this->getOrigNationCode();

		// To: Customer
		$url .= '&DestCityName=' . $this->getDestCityName();
		$url .= '&DestStateCode=' . $this->getDestStateCode();
		$url .= '&DestZipCode=' . $this->getDestZip();
		$url .= '&DestNationCode=' . $this->getDestNationCode();

		// About: Product information
		$url .= '&TotalWeight=' . $this->getTotalWeight($request);
		$url .= '&LineItemCount=' . $this->getLineItemCount();
		$url .= '&LineItemNmfcClass1=' . $this->getLineItemNmfcClass();
		$url .= '&LineitemWeight1=' . $this->getTotalWeight($request);
		$url .= '&ServiceClass=' . $this->getServiceClass();
		$url .= '&PickupDate=' . $this->getPickupDate();

		// Style: XML Query
		$url .= '&TypeQuery=' . $this->getTypeQuery();

		return $url;
	}

	/**
	 * Get status of available tracking
	 *
	 * @param void
	 * @return bool
	 */
	public function isTrackingAvailable()
	{
		return true;
	}

	/**
	 * Get allowed methods for Shippinh Carrier Implementation
	 *
	 * @param void
	 * @return bool
	 */
	public function getAllowedMethods()
	{
		return array(
			'yrc_shipping' => 'Freight Cost',
			);
	}
}
