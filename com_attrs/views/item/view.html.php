<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

class AttrsViewItem extends HtmlView
{
	public $form;
	public $item;

	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');

		if (count($errors = $this->get('Errors'))) {
			\JError::raiseError(500, implode('\n', $errors));
			return false;
		}

		$isNew = $this->item->id == 0;
		ToolBarHelper::title(Text::_('COM_ATTRS_ITEM_TITLE_' . ($isNew ? 'ADD' : 'MOD')), 'puzzle');

		$canDo = ContentHelper::getActions('com_attrs');
		if ($canDo->get('core.manage')) {
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