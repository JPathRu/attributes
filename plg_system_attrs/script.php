<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class plgSystemAttrsInstallerScript
{
    public function preflight($type, $parent)
    {
        if ($type == 'uninstall') {
            return true;
        }
        
        try {
            if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_attrs/attrs.php')) {
                throw new Exception(Text::_('PLG_ATTRS_NOT_COMPONENT'));
                return false;
            }
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
        return true;
    }

    public function postflight($type, $parent)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->update('`#__extensions`')
            ->set('`enabled` = 1')
            ->where('`element` = ' . $db->quote('attrs'))
            ->where('`type` = ' . $db->quote('plugin'))
            ->where('`folder` = ' . $db->quote('system'));
        $db->setQuery($query)->execute();
    }
}
