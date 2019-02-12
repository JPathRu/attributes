<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

class AttrsController extends BaseController
{

	function display($cachable = false, $urlparams = [])
	{
		$this->default_view = 'items';
		parent::display($cachable, $urlparams);
		return $this;
	}

	public function getAjax()
	{
		$input = Factory::getApplication()->input;
		$model = $this->getModel('ajax');
		$action = $input->getCmd('action');
		$reflection = new ReflectionClass($model);
		$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
		$methodList = [];
		foreach ($methods as $method) {
			$methodList[] = $method->name;
		}
		if (in_array($action, $methodList)) {
			$model->$action();
		}
		exit;
	}
}
