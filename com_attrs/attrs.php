<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;

if (!Factory::getUser()->authorise('core.manage', 'com_attrs')) {
	return \JError::raiseWarning(403, Text::_('JERROR_ALERTNOAUTHOR'));
}

$controller = BaseController::getInstance('attrs');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
