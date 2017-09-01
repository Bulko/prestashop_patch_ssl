<?php
/**
 * This file will be removed in 1.6
 */
if (isset(Context::getContext()->controller))
	$controller = Context::getContext()->controller;
else
{
	$controller = new FrontController();
	$controller->init();
}
$protocol = Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://';