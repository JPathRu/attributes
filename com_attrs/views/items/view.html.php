<?php defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Symfony\Component\Yaml\Exception\RuntimeException;

class AttrsViewItems extends HtmlView
{
	public $items;
	public $pagination;
	public $state;
	public $canDo;
	public $allCount;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->allCount = $this->get('ListCount');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		if (count($errors = $this->get('Errors'))) {
			throw new RuntimeException(implode("\n", $errors), 500);
		}

		ToolbarHelper::title(Text::_('COM_ATTRS'), 'puzzle');

		$this->canDo = ContentHelper::getActions('com_attrs')->get('core.manage');
		if ($this->canDo) {
			ToolbarHelper::addNew('item.add');
			if (count($this->items) > 0) {
				ToolbarHelper::editList('item.edit');
				ToolbarHelper::publish('items.publish', 'JTOOLBAR_PUBLISH', true);
				ToolbarHelper::unpublish('items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
				ToolbarHelper::deleteList('COM_ATTRS_DELETE_QUERY_STRING', 'items.delete', 'JTOOLBAR_DELETE');
			}
		}

		$custom_button_html =
			'<span style="display:inline-block;padding:0 15px;font-size:12px;line-height:25.5px;border:1px solid #d6e9c6;border-radius:3px;background-color:#dff0d8;color:#3c763d;">' .
			Text::sprintf('COM_ATTRS_COUNT_ITEMS_VIEW', count($this->items), $this->allCount) .
			'</span>';
		Toolbar::getInstance('toolbar')->appendButton('Custom', $custom_button_html, 'options');
		
		if (ContentHelper::getActions('com_attrs')->get('core.admin')) {
			ToolbarHelper::preferences('com_attrs');
		}

		parent::display($tpl);
	}
}
