<?php
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

if( ModArticleDetailsHelper::isContentArticle() )
{
	$data				= ModArticleDetailsHelper::getList($params);
	$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));

	require JModuleHelper::getLayoutPath('mod_article_details', $params->get('layout', 'default'));
}