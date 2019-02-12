<?php defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class AttrsControllerItems extends AdminController
{
	function __construct($config = [])
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Item', $prefix = 'AttrsModel', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}
}