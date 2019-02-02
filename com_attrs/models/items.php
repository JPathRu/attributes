<?php defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class AttrsModelItems extends ListModel
{
	public function __construct($config = [])
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = [
				'published',
				'name',
				'tp',
				'destsystem',
				'destmenu',
				'destarticles',
				'destcategories',
				'destmodules',
				'destplugins',
				'id'
			];
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = 'title', $direction = 'asc')
	{
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string'));
		$this->setState('filter.tp', $this->getUserStateFromRequest($this->context . '.filter.tp', 'filter_tp', '', 'string'));
		$this->setState('filter.dest', $this->getUserStateFromRequest($this->context . '.filter.dest', 'filter_dest', '', 'string'));
		parent::populateState($ordering, $direction);
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.tp');
		$id .= ':' . $this->getState('filter.dest');
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$query = $this->getDbo()->getQuery(true)
			->select('id, published, title, name, tp, multiple, filter, destsystem, destmenu, destarticles, destcategories, destmodules, destplugins')
			->from('#__attrs');

		$published = $this->getState('filter.published');
		if ($published !== '') {
			$query->where('published = ' . (int)$published);
		}

		$tp = $this->getState('filter.tp');
		if ($tp !== '') {
			$query->where('tp = ' . $this->getDbo()->Quote($tp));
		}

		$dest = $this->getState('filter.dest');
		if ($dest !== '') {
			$query->where($dest . ' = 1');
		}

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $this->getDbo()->Quote('%' . $this->getDbo()->escape($search, true) . '%');
			$query->where('(name like ' . $search . ' or title like ' . $search . ')');
		}

		$listOrder = $this->getState('list.ordering', 'name');
		$listDirn = $this->getState('list.direction', 'asc');
		$query->order($this->_db->quoteName($listOrder) . ' ' . $this->_db->escape($listDirn));

		return $query;
	}

	public function getListCount()
	{
		return (int)$this->getDbo()->setQuery('select count(*) from `#__attrs`')->loadResult();
	}
}