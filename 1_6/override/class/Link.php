<?php
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
	/*
	* module: vipadvancedurl
	* date: 2016-03-17 11:20:31
	* version: 1.3.2
	*/
	public function getCategoryLink( $category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false )
	{
		$dispatcher = Dispatcher::getInstance();
		if (!$id_lang)
		{
			$id_lang = Context::getContext()->language->id;
		}
		$url = $this->getBaseLink($id_shop, null, $relative_protocol).$this->getLangLink($id_lang, null, $id_shop);
		if (!is_object($category))
		{
			$category = new Category($category, $id_lang);
		}
		$params = array();
		$params['id'] = $category->id;
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['meta_keywords'] =  Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
		if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories', $id_shop))
		{
			$p = array();
			foreach ($category->getParentsCategories($id_lang) as $c)
			{
				if (!in_array($c['id_category'], Link::$category_disable_rewrite)
					&& $c['id_category'] != $category->id)
				{
					$p[$c['level_depth']] = $c['link_rewrite'];
				}
			}
			$params['categories'] = implode('/', array_reverse($p));
		}
		$selected_filters = is_null($selected_filters) ? '' : $selected_filters;
		if (empty($selected_filters))
		{
			$rule = 'category_rule';
		}
		else
		{
			$rule = 'layered_rule';
			$params['selected_filters'] = $selected_filters;
		}
		return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
	}
	public function getCatImageMenuLink($name, $id_category, $type = null)
	{
		if ($this->allow == 1 && $type)
		{
			$uri_path = __PS_BASE_URI__.'c/menu/'.$id_category.'-'.$type.'/'.$name.'.jpg';
		}
		else
		{
			$uri_path = _THEME_CAT_DIR_.'menu/'.$id_category.($type ? '-'.$type : '').'.jpg';
		}
		$return = $this->protocol_content.Tools::getMediaServer($uri_path).$uri_path;
		$validuri = explode( 'http', $return );
		if ( !isset($validuri[1]) )
		{
			$protocol = Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://';
			$return = $protocol.$return;
		}
		return $return;
	}
}