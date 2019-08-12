<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

class AttrsHelper
{
    public const ATTR_DEST_SYSTEM = 'system';
    public const ATTR_DEST_MENU = 'menu';
    public const ATTR_DEST_USERS = 'users';
    public const ATTR_DEST_CONTACTS = 'contacts';
    public const ATTR_DEST_ARTICLES = 'articles';
    public const ATTR_DEST_CATEGORIES = 'categories';
    public const ATTR_DEST_MODULES = 'modules';
    public const ATTR_DEST_PLUGINS = 'plugins';
    public const ATTR_DEST_FIELDS = 'fields';
    public const ATTR_DEST_TAGS = 'tags';

    public static function isPublished($attrName)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('`published`')
            ->from('`#__attrs`')
            ->where('`name` = ' . $db->quote(self::getSystemAttrName($attrName)));
        $published = $db->setQuery($query)->loadResult();
        return (bool) $published;
    }

    public static function getAttr($attrName, $attrDest, $id = 0)
    {
        $attrValue = '';

        if (!self::isPublished($attrName)) {
            return $attrValue;
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

        return $attrValue;
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
}
