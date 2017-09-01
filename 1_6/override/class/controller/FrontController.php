<?php
class FrontController extends FrontControllerCore
{
	/**
	 *[canonicalRedirection description FORCE SSL]
	 *@author Golga <r-ro@bulko.net>
	 *@since
	 *@see https://github.com/fugudesign/deco-en-ligne/blob/5286b4cabeedbb2b501da7ffc3e9160f74246ccd/override/classes/controller/FrontController.php
	 *@param  string $canonical_url [description]
	 *@return [type] [description]
	 */
	protected function canonicalRedirection( $canonical_url = '' )
	{
		if (!$canonical_url || !Configuration::get('PS_CANONICAL_REDIRECT') || strtoupper($_SERVER['REQUEST_METHOD']) != 'GET' || Tools::getValue('live_edit'))
		{
			return;
		}
		$match_url = (($this->ssl && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$match_url = rawurldecode($match_url);
		if ( !preg_match('/^'.Tools::pRegexp(rawurldecode($canonical_url), '/').'([&?].*)?$/', $match_url) )
		{
			$params = array();
			$str_params = '';
			$url_details = parse_url($canonical_url);
			if ( !empty($url_details['query']) )
			{
				parse_str($url_details['query'], $query);
				foreach ($query as $key => $value)
				{
					$params[Tools::safeOutput($key)] = Tools::safeOutput($value);
				}
			}
			$excluded_key = array('isolang', 'id_lang', 'controller', 'fc', 'id_product', 'id_category', 'id_manufacturer', 'id_supplier', 'id_cms');
			foreach ($_GET as $key => $value)
			{
				if (!in_array($key, $excluded_key) && Validate::isUrl($key) && Validate::isUrl($value))
				{
					$params[Tools::safeOutput($key)] = Tools::safeOutput($value);
				}
			}
			$str_params = http_build_query($params, '', '&');
			if ( !empty($str_params) )
			{
				$final_url = preg_replace('/^([^?]*)?.*$/', '$1', $canonical_url).'/'.Tools::getValue('selected_filters');
			}
			else
			{
				$final_url = preg_replace('/^([^?]*)?.*$/', '$1', $canonical_url);
			}
			// Don't send any cookie
			Context::getContext()->cookie->disallowWriting();
			if (defined('_PS_MODE_DEV_') && _PS_MODE_DEV_ && $_SERVER['REQUEST_URI'] != __PS_BASE_URI__)
			{
				die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$final_url.'">'.$final_url.'</a>');
			}
			$urlcurrent="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			if ($final_url!=$urlcurrent)
			{
				header('HTTP/1.0 301 Moved');
				header('Cache-Control: no-cache');
				Tools::redirectLink($final_url);
			}
		}
	}
}
/* TOOL Smarty Method */

/**
 *[displayHashedName description]
 *@author Golga <r-ro@bulko.net>
 *@since
 *@see
 *@param  String       $name
 *@param  Bool|boolean $gender enable or disable gender display in hashed name
 *@return Void
 */
function displayHashedName( $name = "[invalid name in FC OV!]", $gender = true )
{
	if ( !empty($name) && $name != NULL )
	{
		$nameHashed = explode( "|", $name );
		if ( is_array( $nameHashed ) && isset( $nameHashed[2] ) && $gender ==  true )
		{
			$displayName = $nameHashed[1] . $nameHashed[2];
		}
		elseif ( is_array( $nameHashed ) && isset( $nameHashed[1] ) )
		{
			$displayName = $nameHashed[1];
		}
		else
		{
			$displayName = $name;
		}
		echo $displayName;
	}
	else
	{
		echo $name;
	}
}
?>
