<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

$isAuthor			= ($data['params']->get('show_author') && !empty($data['item']->author ));
$isParentCategory	= ($data['params']->get('show_parent_category') && !empty($data['item']->parent_slug));
$isCategory			= ($data['params']->get('show_category'));
$isPublishDate		= ($data['params']->get('show_publish_date'));
$isCreateDate		= ($data['params']->get('show_create_date'));
$isModifyDate		= ($data['params']->get('show_modify_date'));
$isHits				= ($data['params']->get('show_hits'));

$cssID		= $data['params']->get('css_id');
$cssClass	= $data['params']->get('css_class');

$cssID		= !empty( $cssID ) ? 'id="' . $cssID . '" ' : '';
$cssClass	= !empty( $cssClass ) ? ' ' . $cssClass : '';
?>
<?php if($isAuthor || $isParentCategory || $isCategory || $isPublishDate || $isCreateDate || $isModifyDate || $isHits) : ?>
<dl <?php echo $cssID; ?>class="article-details<?php echo $cssClass; ?>">

	<?php if ($isAuthor) : ?>
	<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
		<?php
			$author = ($data['item']->created_by_alias ? $data['item']->created_by_alias : $data['item']->author);
			$author = '<span itemprop="name">' . $author . '</span>';
		?>
		<?php if (!empty($data['item']->contact_link ) && $data['params']->get('link_author') == true) : ?>
			<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', $data['item']->contact_link, $author, array('itemprop' => 'url'))); ?>
		<?php else :?>
			<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?>

	<?php if ($isParentCategory) : ?>
	<dd class="parent-category-name">
		<?php $title = $data['item']->parent_title; ?>
		<?php if ($data['params']->get('link_parent_category') && !empty($data['item']->parent_slug)) : ?>
			<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($data['item']->parent_slug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
		<?php else : ?>
			<?php echo JText::sprintf('COM_CONTENT_PARENT', '<span itemprop="genre">' . $title . '</span>'); ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?>

	<?php if ($isCategory) : ?>
	<dd class="category-name">
		<?php $title = $data['item']->category_title; ?>
		<?php if ($data['params']->get('link_category') && $data['item']->catslug) : ?>
			<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($data['item']->catslug)) . '" itemprop="genre">' . $title . '</a>'; ?>
			<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
		<?php else : ?>
			<?php echo JText::sprintf('COM_CONTENT_CATEGORY', '<span itemprop="genre">' . $title . '</span>'); ?>
		<?php endif; ?>
	</dd>
	<?php endif; ?>

	<?php if ($isPublishDate) : ?>
	<dd class="published">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $data['item']->publish_up, 'c'); ?>" itemprop="datePublished">
			<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $data['item']->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
		</time>
	</dd>
	<?php endif; ?>
	
	<?php if ($isCreateDate) : ?>
	<dd class="create">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $data['item']->created, 'c'); ?>" itemprop="dateCreated">
			<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $data['item']->created, JText::_('DATE_FORMAT_LC3'))); ?>
		</time>
	</dd>
	<?php endif; ?>

	<?php if ($isModifyDate) : ?>
	<dd class="modified">
		<span class="icon-calendar"></span>
		<time datetime="<?php echo JHtml::_('date', $data['item']->modified, 'c'); ?>" itemprop="dateModified">
			<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $data['item']->modified, JText::_('DATE_FORMAT_LC3'))); ?>
		</time>
	</dd>
	<?php endif; ?>

	<?php if ($isHits) : ?>
	<dd class="hits">
		<span class="icon-eye-open"></span>
		<meta itemprop="interactionCount" content="UserPageVisits:<?php echo $data['item']->hits; ?>" />
		<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $data['item']->hits); ?>
	</dd>
	<?php endif; ?>
	
</dl>
<?php endif; ?>