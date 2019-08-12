<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;
use Symfony\Component\Yaml\Exception\RuntimeException;

if (!Factory::getUser()->authorise('core.manage', 'com_attrs')) {
	throw new RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

$controller = BaseController::getInstance('attrs');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
