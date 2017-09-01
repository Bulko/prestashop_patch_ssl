<?php
/**
* This source file is subject to the Open Software License (OSL 3.0)
*
*  @author    Vipcom <info@vipcom.vn>
*  @copyright 2015 Vipcom
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
if (version_compare(_PS_VERSION_, '1.6.0.11', '>='))
{
class Link extends LinkCore
{
	/**
	 *[__construct  FORCE SSL]
	 *@author Golga <r-ro@bulko.net>
	 *@since
	 *@see https://github.com/fugudesign/deco-en-ligne/blob/5286b4cabeedbb2b501da7ffc3e9160f74246ccd/override/classes/Link.php
	 *@param  [type] $protocol_link    [description]
	 *@param  [type] $protocol_content [description]
	 */
	public function __construct( $protocol_link = null, $protocol_content = null )
	{
		$this->allow = (int)Configuration::get('PS_REWRITING_SETTINGS');
		$this->url = $_SERVER['SCRIPT_NAME'];
		$this->protocol_link = $protocol_link;
		$this->protocol_content = $protocol_content;
		if (!defined('_PS_BASE_URL_'))
		{
			define('_PS_BASE_URL_', Tools::getShopDomainSsl(true));
		}
		if (!defined('_PS_BASE_URL_SSL_'))
		{
			define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
		}
		if (Link::$category_disable_rewrite === null)
		{
			Link::$category_disable_rewrite = array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'));
		}
		$this->ssl_enable = Configuration::get('PS_SSL_ENABLED');
	}
	public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false)
	{
		$dispatcher = Dispatcher::getInstance();
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;
		$url = $this->getBaseLink($id_shop, null, $relative_protocol).$this->getLangLink($id_lang, null, $id_shop);
		if (!is_object($category))
			$category = new Category($category, $id_lang);
		$params = array();
		$params['id'] = $category->id;
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['meta_keywords'] =	Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
		if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories', $id_shop))
		{
			$p = array();
			foreach ($category->getParentsCategories($id_lang) as $c)
			{
				if (!in_array($c['id_category'], Link::$category_disable_rewrite) && $c['id_category'] != $category->id)
					$p[$c['level_depth']] = $c['link_rewrite'];
			}
			$params['categories'] = implode('/', array_reverse($p));
		}
		// Selected filters is used by the module blocklayered
		$selected_filters = is_null($selected_filters) ? '' : $selected_filters;
		if (empty($selected_filters))
			$rule = 'category_rule';
		else
		{
			$rule = 'layered_rule';
			$params['selected_filters'] = $selected_filters;
		}
		return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
	}
}
}
else if (version_compare(_PS_VERSION_, '1.5.5.0', '>='))
{
class Link extends LinkCore
{
	/**
	 *[__construct  FORCE SSL]
	 *@author Golga <r-ro@bulko.net>
	 *@since
	 *@see https://github.com/fugudesign/deco-en-ligne/blob/5286b4cabeedbb2b501da7ffc3e9160f74246ccd/override/classes/Link.php
	 *@param  [type] $protocol_link    [description]
	 *@param  [type] $protocol_content [description]
	 */
	public function __construct( $protocol_link = null, $protocol_content = null )
	{
		$this->allow = (int)Configuration::get('PS_REWRITING_SETTINGS');
		$this->url = $_SERVER['SCRIPT_NAME'];
		$this->protocol_link = $protocol_link;
		$this->protocol_content = $protocol_content;
		if (!defined('_PS_BASE_URL_'))
		{
			define('_PS_BASE_URL_', Tools::getShopDomainSsl(true));
		}
		if (!defined('_PS_BASE_URL_SSL_'))
		{
			define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
		}
		if (Link::$category_disable_rewrite === null)
		{
			Link::$category_disable_rewrite = array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'));
		}
		$this->ssl_enable = Configuration::get('PS_SSL_ENABLED');
	}
	public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null)
	{
		$dispatcher = Dispatcher::getInstance();
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;
		$url = $this->getBaseLink($id_shop).$this->getLangLink($id_lang, null, $id_shop);
		if (!is_object($category))
			$category = new Category($category, $id_lang);
		$params = array();
		$params['id'] = $category->id;
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['meta_keywords'] =	Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
		if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories', $id_shop))
		{
			$p = array();
			foreach ($category->getParentsCategories($id_lang) as $c)
			{
				if (!in_array($c['id_category'], Link::$category_disable_rewrite) && $c['id_category'] != $category->id)
					$p[$c['level_depth']] = $c['link_rewrite'];
			}
			$params['categories'] = implode('/', array_reverse($p));
		}
		$selected_filters = is_null($selected_filters) ? '' : $selected_filters;
		if (empty($selected_filters))
			$rule = 'category_rule';
		else
		{
			$rule = 'layered_rule';
			$params['selected_filters'] = $selected_filters;
		}
		return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
	}
}
}
else
{
class Link extends LinkCore
{
	protected static $category_disable_rewrite = null;
	/**
	 *[__construct  FORCE SSL]
	 *@author Golga <r-ro@bulko.net>
	 *@since
	 *@see https://github.com/fugudesign/deco-en-ligne/blob/5286b4cabeedbb2b501da7ffc3e9160f74246ccd/override/classes/Link.php
	 *@param  [type] $protocol_link    [description]
	 *@param  [type] $protocol_content [description]
	 */
	public function __construct( $protocol_link = null, $protocol_content = null )
	{
		$this->allow = (int)Configuration::get('PS_REWRITING_SETTINGS');
		$this->url = $_SERVER['SCRIPT_NAME'];
		$this->protocol_link = $protocol_link;
		$this->protocol_content = $protocol_content;
		if (!defined('_PS_BASE_URL_'))
		{
			define('_PS_BASE_URL_', Tools::getShopDomainSsl(true));
		}
		if (!defined('_PS_BASE_URL_SSL_'))
		{
			define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
		}
		if (Link::$category_disable_rewrite === null)
		{
			Link::$category_disable_rewrite = array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'));
		}
		$this->ssl_enable = Configuration::get('PS_SSL_ENABLED');
	}
	public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null)
	{
		$dispatcher = Dispatcher::getInstance();
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;
		$url = _PS_BASE_URL_.__PS_BASE_URI__.$this->getLangLink($id_lang);
		if (!is_object($category))
			$category = new Category($category, $id_lang);
		$params = array();
		$params['id'] = $category->id;
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['meta_keywords'] =	Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
		if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories'))
		{
			$p = array();
			foreach ($category->getParentsCategories($id_lang) as $c)
			{
				if (!in_array($c['id_category'], Link::$category_disable_rewrite) && $c['id_category'] != $category->id)
					$p[$c['level_depth']] = $c['link_rewrite'];
			}
			$params['categories'] = implode('/', array_reverse($p));
		}
		$selected_filters = is_null($selected_filters) ? Tools::getValue('selected_filters') : $selected_filters;
		if (empty($selected_filters))
			$rule = 'category_rule';
		else
		{
			$rule = 'layered_rule';
			$params['selected_filters'] = $selected_filters;
		}
		return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow);
	}
	public function getProductLink($product, $alias = null, $category = null, $ean13 = null, $id_lang = null, $id_shop = null,
		$ipa = 0, $force_routes = false)
	{
		$dispatcher = Dispatcher::getInstance();
		if (!$id_lang)
			$id_lang = Context::getContext()->language->id;
		if (!$id_shop)
			$shop = Context::getContext()->shop;
		else
			$shop = new Shop($id_shop);
		$url = $shop->getBaseURL().$this->getLangLink($id_lang);
		if (!is_object($product))
		{
			if (is_array($product) && isset($product['id_product']))
					$product = new Product($product['id_product'], false, $id_lang);
			else if (is_numeric($product) || !$product)
				$product = new Product($product, false, $id_lang);
			else
				throw new PrestaShopException('Invalid product vars');
		}
		// Set available keywords
		$params = array();
		$params['id'] = $product->id;
		$params['rewrite'] = (!$alias) ? $product->getFieldByLang('link_rewrite') : $alias;
		$params['ean13'] = (!$ean13) ? $product->ean13 : $ean13;
		$params['meta_keywords'] =	Tools::str2url($product->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($product->getFieldByLang('meta_title'));
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'manufacturer'))
			$params['manufacturer'] = Tools::str2url($product->isFullyLoaded ? $product->manufacturer_name :
				Manufacturer::getNameById($product->id_manufacturer));
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'supplier'))
			$params['supplier'] = Tools::str2url($product->isFullyLoaded ? $product->supplier_name : Supplier::getNameById($product->id_supplier));
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'price'))
			$params['price'] = $product->isFullyLoaded ? $product->price :
				Product::getPriceStatic($product->id, false, null, 6, null, false, true, 1, false, null, null, null, $product->specificPrice);
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'tags'))
			$params['tags'] = Tools::str2url($product->getTags($id_lang));
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'category'))
			$params['category'] = (!is_null($product->category) && !empty($product->category)) ? Tools::str2url($product->category) :
				Tools::str2url($category);
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'reference'))
			$params['reference'] = Tools::str2url($product->reference);
		if ($dispatcher->hasKeyword('product_rule', $id_lang, 'categories'))
		{
			$params['category'] = (!$category) ? $product->category : $category;
			$cats = array();
			foreach ($product->getParentCategories($id_lang) as $cat)
				if (!in_array($cat['id_category'], Link::$category_disable_rewrite))
					$cats[] = $cat['link_rewrite'];
			$params['categories'] = implode('/', $cats);
		}
		$anchor = $ipa ? $product->getAnchor($ipa) : '';
		return $url.$dispatcher->createUrl('product_rule', $id_lang, $params, $force_routes, $anchor);
	}
}
}