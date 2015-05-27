<?php
defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

abstract class ModArticleDetailsHelper
{
	public static function getList(&$params)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$doc	= JFactory::getDocument();
		$lang	= JFactory::getLanguage();
		$query	= $db->getQuery(true);
		
		$id		= $app->input->getCmd('id', '');
		$option	= $app->input->getCmd('option', '');
		
		$query->from('#__content AS a');
		
		$query->select('a.id, a.alias, a.hits, a.catid, a.created, a.created_by_alias as created_by_alias, a.publish_up, a.created_by as contactid, a.language as language');

		$query->select('u.name AS author');
		$query->join('LEFT', '#__users AS u on u.id = a.created_by');
		
		$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
		$query->join('LEFT', '#__categories AS c on c.id = a.catid');
		
		$query->select('CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified');
		
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as catslug, parent.alias as parent_alias');
		$query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');
		
		$query->where('a.id = ' . $id);
			  
		$db->setQuery($query);
		$item = $db->loadObject();
		
		$contactid = self::getContactID($item->contactid);
		
		if ($contactid)
		{
			$needle = 'index.php?option=com_contact&view=contact&id=' . $contactid;
			$menu = JFactory::getApplication()->getMenu();
			$menuItem = $menu->getItems('link', $needle, true);
			$link = $menuItem ? $needle . '&Itemid=' . $menuItem->id : $needle;
			$item->contact_link = JRoute::_($link);
		}
		else
		{
			$item->contact_link = '';
		}
		
		$item->slug			= $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		$item->catslug		= $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		$item->parent_slug	= $item->parent_alias ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

		if ($item->parent_alias == 'root')
		{
			$item->parent_slug = null;
		}
		
		$item->hits = $item->hits - 1;

		$articles_details['item'] = $item;
		$articles_details['params'] = $params;
		
		return $articles_details;
	}
	
	protected static function getContactID($created_by)
	{
		$db		= JFactory::getDbo();
		
		static $contacts = array();

		if (isset($contacts[$created_by]))
		{
			return $contacts[$created_by];
		}

		$query	= $db->getQuery(true);

		$query->select('MAX(contact.id) AS contactid');
		$query->from($db->quoteName('#__contact_details', 'contact'));
		$query->where('contact.published = 1');
		$query->where('contact.user_id = ' . (int) $created_by);

		if (JLanguageMultilang::isEnabled() == 1)
		{
			$query->where('(contact.language in '
				. '(' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ') '
				. ' OR contact.language IS NULL)');
		}

		$db->setQuery($query);

		$contacts[$created_by] = $db->loadResult();

		return $contacts[$created_by];
	}
	
	public static function isContentArticle()
	{
		$app	= JFactory::getApplication();
		$id		= $app->input->getCmd('id', '');
		$option	= $app->input->getCmd('option', '');
		$view	= $app->input->getCmd('view', '');
		
		$isId = !empty($id);
		$isView = (!empty($view) && $view == 'article');
		$isContent = (!empty($option) && $option == 'com_content');
		
		if($isId && $isView && $isContent)
		{
			return true;
		}
		
		return false;
	}
}
