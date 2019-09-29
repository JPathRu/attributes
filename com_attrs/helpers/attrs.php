<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Layout\FileLayout;

class AttrsHelper
{
    const ATTR_DEST_SYSTEM = 'system';
    const ATTR_DEST_MENU = 'menu';
    const ATTR_DEST_USERS = 'users';
    const ATTR_DEST_CONTACTS = 'contacts';
    const ATTR_DEST_ARTICLES = 'articles';
    const ATTR_DEST_CATEGORIES = 'categories';
    const ATTR_DEST_MODULES = 'modules';
    const ATTR_DEST_PLUGINS = 'plugins';
    const ATTR_DEST_FIELDS = 'fields';
    const ATTR_DEST_TAGS = 'tags';

    public static function getAttr($attrName, $attrDest, $id = 0, $template = '')
    {
        $attrValue = '';

        $attrParams = self::getAttrParams($attrName);
        if (!isset($attrParams) || !$attrParams['published']) {
            return $attrValue;
        }
        if (!$attrParams['layout']) {
            $attrParams['layout'] = '_:default';
        }
        
        if (!$id && $attrDest !== self::ATTR_DEST_SYSTEM) {
            return $attrValue;
        }

        $attrName = self::getParamsAttrName($attrName);

        switch ($attrDest) {
            case self::ATTR_DEST_SYSTEM:
                $attrValue = Factory::getConfig()->get(str_replace('-', '_', $attrName), '');
                break;

            case self::ATTR_DEST_MENU:
                $table = Table::getInstance('Menu');
                break;

            case self::ATTR_DEST_USERS:
                $table = Table::getInstance('User');
                break;

            case self::ATTR_DEST_CONTACTS:
                $db = Factory::getDbo();
                $query = $db->getQuery(true)
                    ->select('`params`')
                    ->from('`#__contact_details`')
                    ->where('`id` = ' . (int) $id);
                try {
                    $data = $db->setQuery($query)->loadResult();
                } catch (Exception $e) {
                    break;
                }
                $params = new Registry($data);
                $attrValue = $params->get($attrName, '');
                break;

            case self::ATTR_DEST_ARTICLES:
                $table = Table::getInstance('Content');
                $table->load($id);
                $params = new Registry($table->attribs);
                $attrValue = $params->get($attrName,  '');
                unset($table);
                break;

            case self::ATTR_DEST_CATEGORIES:
                $table = Table::getInstance('Category');
                break;

            case self::ATTR_DEST_MODULES:
                $table = Table::getInstance('Module');
                break;

            case self::ATTR_DEST_PLUGINS:
                $db = Factory::getDbo();
                $query = $db->getQuery(true)
                    ->select('`params`')
                    ->from('`#__extensions`')
                    ->where('`extension_id` = ' . (int) $id);
                try {
                    $data = $db->setQuery($query)->loadResult();
                } catch (Exception $e) {
                    break;
                }
                $params = new Registry($data);
                $attrValue = $params->get($attrName, '');
                break;

            case self::ATTR_DEST_FIELDS:
                $db = Factory::getDbo();
                $query = $db->getQuery(true)
                    ->select('`params`')
                    ->from('`#__fields`')
                    ->where('`id` = ' . (int) $id);
                try {
                    $data = $db->setQuery($query)->loadResult();
                } catch (Exception $e) {
                    break;
                }
                $params = new Registry($data);
                $attrValue = $params->get($attrName, '');
                break;

            case self::ATTR_DEST_TAGS:
                $db = Factory::getDbo();
                $query = $db->getQuery(true)
                    ->select('`params`')
                    ->from('`#__tags`')
                    ->where('`id` = ' . (int) $id);
                try {
                    $data = $db->setQuery($query)->loadResult();
                } catch (Exception $e) {
                    break;
                }
                $params = new Registry($data);
                $attrValue = $params->get($attrName, '');
                break;
        }

        if (isset($table)) {
            $table->load($id);
            $params = new Registry($table->params);
            $attrValue = $params->get($attrName, '');
        }

        if ($template === false) {
            return $attrValue;
        } else {
            $layoutMask = $template ? $template : $attrParams['layout'];
            $fileLayout = self::getLayoutPath($layoutMask);
            $layout = new FileLayout(pathinfo($fileLayout, PATHINFO_FILENAME), null, ['component' => 'com_attrs']);
            $layout->addIncludePath(pathinfo($fileLayout, PATHINFO_DIRNAME));
            return $layout->render(['name' => self::getSystemAttrName($attrName), 'value' => $attrValue, 'dest' => $attrDest, 'id' => $id]);
        }
    }

    private static function getAttrParams($attrName)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('`published`, `layout`')
            ->from('`#__attrs`')
            ->where('`name` = ' . $db->quote(self::getSystemAttrName($attrName)));
        try {
            $row = $db->setQuery($query)->loadAssoc();
        } catch (\Exception $e) {
            $row = [];
        }
        return $row;
    }

    private static function getSystemAttrName($attrName)
    {
        return str_replace('attrs_', '', $attrName);
    }

    private static function getParamsAttrName($attrName)
    {
        if (strpos($attrName, 'attrs_') !== 0) {
            $attrName = 'attrs_' . $attrName;
        }
        return $attrName;
    }
    
    private static function getLayoutPath($layout = 'default')
    {
        $template = Factory::getApplication()->getTemplate();
        $defaultLayout = $layout;

        if (strpos($layout, ':') !== false) {
            $temp = explode(':', $layout);
            $template = $temp[0] === '_' ? $template : $temp[0];
            $layout = $temp[1];
            $defaultLayout = $temp[1] ?: 'default';
        }

        $tPath = JPATH_THEMES . '/' . $template . '/html/layouts/com_attrs/' . $layout . '.php';
        $bPath = JPATH_ADMINISTRATOR . '/components/com_attrs/layouts/' . $defaultLayout . '.php';
        $dPath = JPATH_ADMINISTRATOR . '/components/com_attrs/layouts/default.php';

        if (file_exists($tPath)) {
            return Path::clean($tPath);
        }

        if (file_exists($bPath)) {
            return Path::clean($bPath);
        }

        return Path::clean($dPath);
    }
}
