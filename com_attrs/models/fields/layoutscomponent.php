<?php defined('JPATH_PLATFORM') or die;
/**
 * @package     Joomla.Legacy
 * @subpackage  Form
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc; Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormField;

class JFormFieldLayoutsComponent extends FormField
{
	protected $type = 'LayoutsComponent';

	protected function getInput()
	{
		$component = explode('.', $this->form->getName())[0];

		$component = preg_replace('#\W#', '', $component);
		$client = ApplicationHelper::getClientInfo(0);
		$client_administrator = ApplicationHelper::getClientInfo(1);

		$lang = Factory::getLanguage();
		$lang->load($component . '.sys', $client_administrator->path, null, false, true) || $lang->load($component . '.sys', $client_administrator->path . '/components/' . $component, null, false, true);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('element, name')
			->from('#__extensions as e')
			->where('e.client_id = 0')
			->where('e.type = ' . $db->quote('template'))
			->where('e.enabled = 1');

		$db->setQuery($query);
		$templates = $db->loadObjectList('element');

		$component_path = Path::clean($client_administrator->path . '/components/' . $component . '/layouts');

		$component_layouts = [];

		$groups = [];

		if (is_dir($component_path) && ($component_layouts = Folder::files($component_path, '^[^_]*\.php$'))) {
			$groups['_'] = [];
			$groups['_']['id'] = $this->id . '__';
			$groups['_']['text'] = Text::sprintf('JOPTION_FROM_COMPONENT');
			$groups['_']['items'] = [];

			foreach ($component_layouts as $file) {
				$value = basename($file, '.php');
				$text = $lang->hasKey($key = strtoupper($component . '_LAYOUTS_LAYOUT_' . $value)) ? Text::_($key) : $value;
				$groups['_']['items'][] = HTMLHelper::_('select.option', '_:' . $value, $text);
			}
		}

		if ($templates) {
			foreach ($templates as $template) {
				$lang->load('tpl_' . $template->element . '.sys', $client->path, null, false, true) || $lang->load('tpl_' . $template->element . '.sys', $client->path . '/templates/' . $template->element, null, false, true);

				$template_path = Path::clean($client->path . '/templates/' . $template->element . '/html/layouts/' . $component);

				if (is_dir($template_path) && ($files = Folder::files($template_path, '^[^_]*\.php$'))) {
					foreach ($files as $i => $file) {
						if (in_array($file, $component_layouts)) {
							unset($files[$i]);
						}
					}

					if (count($files)) {
						$groups[$template->element] = [];
						$groups[$template->element]['id'] = $this->id . '_' . $template->element;
						$groups[$template->element]['text'] = Text::sprintf('JOPTION_FROM_TEMPLATE', $template->name);
						$groups[$template->element]['items'] = [];

						foreach ($files as $file) {
							$value = basename($file, '.php');
							$text = $lang->hasKey($key = strtoupper('TPL_' . $template->element . '_' . $component . '_LAYOUTS_LAYOUT_' . $value)) ? Text::_($key) : $value;
							$groups[$template->element]['items'][] = HTMLHelper::_('select.option', $template->element . ':' . $value, $text);
						}
					}
				}
			}
		}
		$attr = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		$html = [];

		$selected = [$this->value];

		$html[] = HTMLHelper::_(
			'select.groupedlist',
			$groups,
			$this->name,
			['id' => $this->id, 'group.id' => 'id', 'list.attr' => $attr, 'list.select' => $selected]
		);

		return implode($html);
	}
}
