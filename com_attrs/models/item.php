<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Filter\OutputFilter;

class AttrsModelItem extends AdminModel
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_attrs.item', 'item', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getTable($type = 'Attrs_List', $prefix = 'Table', $config = [])
    {
        return Table::getInstance($type, $prefix, $config);
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_attrs.edit.item.data', []);
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    protected function canDelete($record)
    {
        if (!empty($record->id)) {
            return Factory::getUser()->authorise('core.manage', 'com_attrs');
        }
    }

    protected function canEditState($record)
    {
        if (!empty($record->id)) {
            return Factory::getUser()->authorise('core.manage', 'com_attrs');
        } else {
            return parent::canEditState('com_attrs');
        }
    }

    public function publish(&$pks, $value = 1)
    {
        return parent::publish($pks, $value);
    }

    public function save($data)
    {
        $data['val'] = json_encode($data['val']);
        if (empty(trim($data['name']))) {
            $data['name'] = trim($data['title']);
        }
        $data['name'] = OutputFilter::stringURLSafe($data['name']);
        return parent::save($data);
    }
}
