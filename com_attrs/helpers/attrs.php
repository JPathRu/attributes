<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class AttrsHelper
{
	public static function getPrearedContent($content, $code = 0)
	{
		if (!$code) {
			return $content;
		}

		$replaced = '';
		for ($i = 0; $i < strlen($content); $i++) {
			$char = $content[$i];
			if ($code == 1) {
				$replaced .= '%' . dechex(ord($char));
			} else {
				$replaced .= '&#' . ord($char) . ';';
			}
		}
		return $replaced;
	}

	public static function getAttr($id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('published, content, code')
			->from('#__attrss')
			->where('id=' . (int)$id);
		$item = $db->setQuery($query)->loadObject();

		if (!$item || !$item->published) {
			return '{snipped ' . $id . '}';
		} else {
			return self::getPrearedContent($item->content, $item->code);
		}
	}
}