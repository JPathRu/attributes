<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Symfony\Component\Yaml\Exception\RuntimeException;

class AttrsViewItem extends HtmlView
{
    public $form;
    public $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new RuntimeException(implode("\n", $errors), 500);
        }

        $isNew = $this->item->id == 0;
        ToolBarHelper::title(Text::_('COM_ATTRS_ITEM_TITLE_' . ($isNew ? 'ADD' : 'MOD')), 'puzzle');
        Factory::getApplication()->input->set('hidemainmenu', true);

        $canDo = ContentHelper::getActions('com_attrs')->get('core.manage');
        if ($canDo) {
            ToolBarHelper::apply('item.apply');
            ToolBarHelper::save('item.save');
            ToolBarHelper::save2new('item.save2new');
            ToolBarHelper::save2new('item.save2copy', 'COM_ATTRS_ITEM_SAVE_TO_COPY');
        }
        if ($isNew) {
            ToolBarHelper::cancel('item.cancel');
        } else {
            ToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
        }

        parent::display($tpl);
    }
}
