<?php defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

class TableAttrs_List extends Table
{
	function __construct(&$db)
	{
		parent::__construct('#__attrs', 'id', $db);
	}
}