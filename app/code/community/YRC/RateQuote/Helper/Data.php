<?php

/**
 * YRC Rate Data Helper
 *
 * @category YRC
 * @package YRC_RateQuote
 * @author Justin Page | KLVTZ
 */
class YRC_RateQuote_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Dump the passed variables and end the scripts
	 *
	 * @param void
	 * @return void
	 */
	public function dd()
	{
		array_map(function($x) { var_dump($x); }, func_get_args()); die; 
	}
}
