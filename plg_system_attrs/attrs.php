<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

JLoader::register('AttrsHelper', JPATH_ADMINISTRATOR . '/components/com_attrs/helpers/attrs.php');

class plgSystemAttrs extends CMSPlugin
{
    private $input;
    
    private $isAdmin;

    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $this->input = new Input();
        $this->isAdmin = Factory::getApplication()->isClient('administrator');
        $this->loadLanguage();
    }

    public function onContentPrepare($context, &$article, $params, $page = 0)
    {
        if (!class_exists('AttrsHelper')) {
            return true;
        }

        if ($context === 'com_finder.indexer') {
            return true;
        }

        if (strpos($article->text, '{attrs;') === false) {
            return true;
        }

        $types = [
            AttrsHelper::ATTR_DEST_SYSTEM,
            AttrsHelper::ATTR_DEST_MENU,
            AttrsHelper::ATTR_DEST_USERS,
            AttrsHelper::ATTR_DEST_CONTACTS,
            AttrsHelper::ATTR_DEST_ARTICLES,
            AttrsHelper::ATTR_DEST_CATEGORIES,
            AttrsHelper::ATTR_DEST_MODULES,
            AttrsHelper::ATTR_DEST_PLUGINS,
            AttrsHelper::ATTR_DEST_FIELDS,
            AttrsHelper::ATTR_DEST_TAGS
        ];

        $matches = [];
        preg_match_all('/{attrs(.*?)}/i', $article->text, $matches, PREG_SET_ORDER);

        if ($matches) {
            foreach ($matches as $match) {
                $matcheslist = explode(';', $match[1]);

                if (!array_key_exists(3, $matcheslist)) {
                    continue;
                }

                $type = strtolower(trim($matcheslist[1]));
                if (!in_array($type, $types)) {
                    continue;
                }

                $id = trim($matcheslist[2]);
                $attrName = trim($matcheslist[3]);

                $output = AttrsHelper::getAttr($attrName, $type, $id);

                if (is_array($output)) {
                    $output = implode(', ', $output);
                }

                $article->text = str_replace($match[0], $output, $article->text);
            }
        }
    }

    public function onContentPrepareForm($form, $data)
    {
        if (!$this->isAdmin) {
            return;
        }

        if (!($form instanceof Form)) {
            return;
        }

        $option = $this->input->getCmd('option', '');
        $formname = $form->getName();

        $isSystem = $option == 'com_config' && $formname == 'com_config.application';
        $isMenu = $option == 'com_menus' && $formname == 'com_menus.item';
        $isUsers = $option == 'com_users' && $formname == 'com_users.user';
        $isContacts = $option == 'com_contact' && $formname == 'com_contact.contact';
        $isArticle = $option == 'com_content' && $formname == 'com_content.article';
        $isCategory = $option == 'com_categories' && strpos($formname, 'com_categories.category') !== false;
        $isModule = ($option == 'com_modules' && $formname == 'com_modules.module') || ($option == 'com_advancedmodules' && $formname == 'com_advancedmodules.module');
        $isPlugin = $option == 'com_plugins' && $formname == 'com_plugins.plugin';
        $isFields = $option == 'com_fields' && strpos($formname, 'com_fields.field') !== false;
        $isTags = $option == 'com_tags' && $formname == 'com_tags.tag';

        $tp = $isSystem ? 'destsystem' : '';
        if (!$tp) {
            $tp = $isMenu ? 'destmenu' : '';
        }
        if (!$tp) {
            $tp = $isUsers ? 'destusers' : '';
        }
        if (!$tp) {
            $tp = $isContacts ? 'destcontacts' : '';
        }
        if (!$tp) {
            $tp = $isArticle ? 'destarticles' : '';
        }
        if (!$tp) {
            $tp = $isCategory ? 'destcategories' : '';
        }
        if (!$tp) {
            $tp = $isModule ? 'destmodules' : '';
        }
        if (!$tp) {
            $tp = $isPlugin ? 'destplugins' : '';
        }
        if (!$tp) {
            $tp = $isFields ? 'destfields' : '';
        }
        if (!$tp) {
            $tp = $isTags ? 'desttags' : '';
        }

        if (!$tp) {
            return;
        }

        $fields = $this->getData($tp);
        if (!$fields) {
            return;
        }

        if (is_array($data)) {
            $data = (object) $data;
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?><form>';

        if ($isSystem) {
            $xml .= '<fieldset name="cookie">';
            $xml .= '<field name="attrssystemspacer" type="spacer" hr="true" />';
            $xml .= '<field name="attrssystemtitle" type="note" label="' . Text::_('PLG_ATTRS_TAB_LABEL') . '"/>';
        } elseif ($isModule) {
            $xml .= '<fieldset name="attrs" label="' . Text::_('PLG_ATTRS_TAB_LABEL') . '"><fields name="params">';
        } elseif ($isArticle) {
            $xml .= '<fields name="attribs"><fieldset name="attrs" label="' . Text::_('PLG_ATTRS_TAB_LABEL') . '">';
        } else {
            $xml .= '<fields name="params"><fieldset name="attrs" label="' . Text::_('PLG_ATTRS_TAB_LABEL') . '">';
        }

        foreach ($fields as $field) {

            $name = ' name="attrs_' . ($isSystem ? str_replace('-', '_', $field->name) : $field->name) . '"';
            $label = ' label="' . $field->title . '"';
            $class = $field->class ? ' class="' . $field->class . '"' : ' class="input-xlarge"';

            switch ($field->tp) {
                case 'text':
                    $xml .= '<field type="text"' . $name . $label . $class . ($field->filter ? ' filter="' . $field->filter . '"' : '') . '/>';
                    break;

                case 'textarea':
                    $xml .= '<field type="textarea"' . $name . $label . $class . ($field->filter ? ' filter="' . $field->filter . '"' : '') . ' rows="5"/>';
                    break;

                case 'editor':
                    $xml .= '<field type="editor"' . $name . $label . ' filter="raw"/>';
                    break;

                case 'list':
                    $xml .= '<field type="list"' . $name . $label . $class . ($field->multiple ? ' multiple="true"' : '') . '>';
                    foreach ($field->val as $val) {
                        $xml .= '<option value="' . $val['vname'] . '">' . $val['vtitle'] . '</option>';
                    }
                    $xml .= '</field>';
                    break;

                case 'media':
                    $xml .= '<field type="media"' . $name . $label . $class . '/>';
                    break;
            }
        }

        if ($isSystem) {
            $xml .= '</fieldset>';
        } elseif ($isModule) {
            $xml .= '</fields></fieldset>';
        } else {
            $xml .= '</fieldset></fields>';
        }

        $xml .= '</form>';

        $xml = new \SimpleXMLElement($xml);
        $form->setFields($xml, null, false);

        return true;
    }

    private function getData($tp)
    {
        $db = Factory::getDbo();

        $query = $db->getQuery(true)
            ->select('`name`, `title`, `tp`, `val`, `multiple`, `filter`, `class`')
            ->from('`#__attrs`')
            ->where($db->quoteName($tp) . ' = 1')
            ->where('`published` = 1')
            ->order('`id` asc');

        try {
            $list = $db->setQuery($query)->loadObjectList();
        } catch (Exception $e) {
            $list = [];
        }

        if ($list) {
            foreach ($list as &$item) {
                if ($item->tp === 'list' && $item->val !== '') {
                    $item->val = json_decode($item->val, true);
                }
            }
        }

        return $list;
    }
}
