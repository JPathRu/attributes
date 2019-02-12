<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

class AttrsControllerItem extends FormController
{

	function __construct($config = [])
	{
		$this->view_list = 'items';
		parent::__construct($config);
	}

	protected function allowEdit($data = [], $key = 'id')
	{
		return Factory::getUser()->authorise('core.manage', 'com_attrs');
	}
}