<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.beez3
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params;
$images = json_decode($this->item->images);
$urls = json_decode($this->item->urls);
$user    = JFactory::getUser();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');

// Create shortcut to parameters.
$params = $this->item->params;

if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : 
	$this->document->setMetadata('og_image', $images->image_fulltext);
endif;
?>
<?php
$urls = json_decode($this->item->urls);
?>

<div class="content-block article">
    <?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
    <?php $imgfloat = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
    <div class="img-fulltext-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?>"> <img class="full"
		<?php if ($images->image_fulltext_caption):
			echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8') .'"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8'); ?>"/> </div>
    <?php endif; ?>
    <article>
        <?php $useDefList = ($params->get('show_author') or $params->get('show_category') or $params->get('show_parent_category')
	or $params->get('show_create_date') or $params->get('show_modify_date') or $params->get('show_publish_date')
	or $params->get('show_hits')); ?>
        <?php if ($useDefList) : ?>
        <dl class="article-info">
            <?php endif; ?>
            <?php if ($params->get('show_parent_category') && $this->item->parent_slug !== '1:root') : ?>
            <dd class="parent-category-name">
                <?php 	$title = $this->escape($this->item->parent_title);
					$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
                <?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
                <?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
                <?php else : ?>
                <?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
                <?php endif; ?>
            </dd>
            <?php endif; ?>
            <?php if ($params->get('show_category')) : ?>
            <dd class="category-name">
                <?php 	$title = $this->escape($this->item->category_title); ?>
                <?php echo $title; ?> </dd>
            <?php endif; ?>
            <?php if ($params->get('show_create_date')) : ?>
            <dd class="create"> <?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?> </dd>
            <?php endif; ?>
            <?php if ($params->get('show_modify_date')) : ?>
            <dd class="modified"> <?php echo  JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')); ?> </dd>
            <?php endif; ?>
            <?php if ($params->get('show_publish_date')) : ?>
            <dd class="published"> <?php echo date('d F Y', strtotime($this->item->publish_up));  ?> </dd>
            <?php endif; ?>
            <?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
            <dd class="createdby">
                <?php $author = $this->item->author; ?>
                <?php $author = ($this->item->created_by_alias ?: $author);?>
                <?php if (!empty($this->item->contact_link ) &&  $params->get('link_author') == true) : ?>
                <?php echo JHtml::_('link', $this->item->contact_link, $author); ?>
                <?php else : ?>
                <?php echo $author; ?>
                <?php endif; ?>
            </dd>
            <?php endif; ?>
            <?php if ($params->get('show_hits')) : ?>
            <dd class="hits"> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?> </dd>
            <?php endif; ?>
            <?php if ($useDefList) : ?>
        </dl>
        <?php endif; ?>
        <?php if ($params->get('show_title')) : ?>
        <hgroup>
            <h2> <?php echo $this->escape($this->item->title); ?> </h2>
            <?php endif; ?>
            <?php if ($this->params->get('show_page_heading') and $params->get('show_title')) :?>
        </hgroup>
        <?php endif; ?>
        <?php  if (!$params->get('show_intro')) :
		echo $this->item->event->afterDisplayTitle;
	endif; ?>
        <?php echo $this->item->event->beforeDisplayContent; ?>
        <?php if (isset ($this->item->toc)) : ?>
        <?php echo $this->item->toc; ?>
        <?php endif; ?>
        <?php
if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
	echo $this->item->pagination;
endif;
?>
        <?php if ($params->get('access-view')):?>
        <?php echo  preg_replace('%<p>(<img .*?/>)</p>%i', '$1', $this->item->text); ?>
        <?php // Optional teaser intro text for guests ?>
        <?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
        <?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?> <?php echo JHtml::_('content.prepare', $this->item->introtext); ?>
        <?php // Optional link to let them register to see the whole article. ?>
        <?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
        <?php $menu = JFactory::getApplication()->getMenu(); ?>
        <?php $active = $menu->getActive(); ?>
        <?php $itemId = $active->id; ?>
        <?php $link = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
        <?php $link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
        <p class="readmore"> <a href="<?php echo $link; ?>" class="register">
            <?php $attribs = json_decode($this->item->attribs); ?>
            <?php if ($attribs->alternative_readmore == null) : ?>
            <?php echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE'); ?>
            <?php elseif ($readmore = $attribs->alternative_readmore) : ?>
            <?php echo $readmore; ?>
            <?php if ($params->get('show_readmore_title', 0) != 0) : ?>
            <?php echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit')); ?>
            <?php endif; ?>
            <?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
            <?php echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE'); ?>
            <?php else : ?>
            <?php echo JText::_('COM_CONTENT_READ_MORE'); ?> <?php echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit')); ?>
            <?php endif; ?>
            </a> </p>
        <?php endif; ?>
        <?php endif; ?>
        <?php if($urls->urla): ?>
        <?php $url = ltrim($urls->urla, '/'); ?>
        <p> <a href="<?php echo $url; ?>" class="btn" target="_blank">View original article</a> </p>
        <?php endif; ?>
        <?php echo $this->item->event->afterDisplayContent; ?> 
    <div class="article-share">
        <h5>SHARE</h5>
        <?php $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
        
        <p> <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $actual_link; ?>" title="Share on Facebook"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 282 282" width="32px" height="32px" style="enable-background:new 0 0 282 282;" xml:space="preserve">
            <g>
                <defs>
                    <rect id="SVGID_1_" width="282" height="282"/>
                </defs>
                <clipPath id="SVGID_2_">
                    <use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
                </clipPath>
                <path class="st0" d="M141,0C63.1,0,0,63.1,0,141s63.1,141,141,141s141-63.1,141-141S218.8,0,141,0 M180.4,70.4
		c-1,6.5-2.1,13-3.1,19.5c-0.3,1.6-0.5,3.3-0.8,4.9c0,0.1-0.1,0.2-0.1,0.3c-0.2,0-0.3,0-0.4-0.1c-3.8-0.9-7.7-1.7-11.6-2
		c-2.3-0.2-4.6-0.2-6.9,0.5c-3.6,1.1-5.8,3.4-6.8,7c-0.6,2.1-0.7,4.2-0.7,6.3c0,4.6,0,9.3,0,13.9c0,0.6,0.2,0.7,0.7,0.7
		c8.3,0,16.6,0,24.8,0c0.2,0,0.3,0,0.4,0c0.4,0,0.6,0.1,0.5,0.6c-0.2,1.9-0.5,3.7-0.7,5.6c-0.4,3-0.7,5.9-1.1,8.9
		c-0.3,2.8-0.7,5.6-1,8.4c0,0.4-0.3,0.4-0.6,0.4c-3.1,0-6.1,0-9.2,0h-13.2H150c0,0.1,0,0.3,0,0.4c0,17.4,0,51.4,0,68.8
		c0,0.7,0,0.9,0.2,0.9h0.7c-0.4,0-0.6,0-0.7,0l-31.8,0v-70.1h-16.2v-23.9c0.3,0,0.5,0,0.8,0c4.9,0,9.8,0,14.7,0c1,0,0.8,0,0.8-0.9
		c0-8.3,0-16.6,0-24.9c0-8.2,3.4-14.7,9.8-19.8c4.9-3.9,10.5-6.2,16.6-7.4c3.9-0.7,7.8-0.9,11.8-0.7c4.1,0.1,8.3,0.3,12.4,0.6
		c3.6,0.3,7.2,0.8,10.8,1.3C180.3,69.8,180.5,69.9,180.4,70.4"/>
                <path class="st1" d="M150.9,215.5h-0.7C150.3,215.5,150.5,215.5,150.9,215.5"/>
            </g>
            </svg> </a> <a href="https://twitter.com/home?status=<?php echo urlencode($this->item->title . ' ' . $actual_link); ?> " title="Share on Twitter"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 282 282"  width="32px" height="32px" style="enable-background:new 0 0 282 282;" xml:space="preserve">
            <style type="text/css">
	.st0{clip-path:url(#SVGID_2_);}
</style>
            <g>
                <defs>
                    <rect id="SVGID_1_" width="282" height="282"/>
                </defs>
                <clipPath id="SVGID_2_">
                    <use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
                </clipPath>
                <path class="st0" d="M141,0C63.1,0,0,63.1,0,141c0,77.9,63.1,141,141,141c77.9,0,141-63.1,141-141C282,63.1,218.8,0,141,0
		 M215.6,110c-2.9,3.5-6.2,6.5-9.8,9.3c-0.4,0.3-0.7,1.1-0.7,1.6c0.3,8.5-0.8,16.9-3.1,25c-1.9,6.9-4.7,13.5-8.2,19.7
		c-2.9,5-6.1,9.7-9.9,14.1c-8.3,9.6-18.4,16.8-30.1,21.6c-9.2,3.7-18.8,5.6-28.7,6c-7,0.2-13.9-0.2-20.7-1.7
		c-6.9-1.5-13.6-3.6-19.8-6.9c-2.4-1.3-4.8-2.6-7.2-4c-0.2-0.1-0.4-0.2-0.8-0.5c16.1,1.5,30.3-2.4,43.2-12.1
		c-13.5-1.2-22.4-7.9-27.4-20.5c4.5,0.7,8.6,0.6,13-0.4c-14.6-4.4-22.3-14-23.3-29.1c2.3,0.9,4.3,1.8,6.4,2.4c2.1,0.6,4.3,0.8,6.5,1
		c-7-5.3-11.3-12.2-12.4-20.9c-0.8-6.4,0.4-12.5,3.7-18.2c15.9,18.7,36,29,60.6,30.7c-0.2-1.9-0.4-3.6-0.5-5.3
		c-0.4-9.7,3.1-17.8,10.5-24.1c9.8-8.3,23.9-9.1,34.7-2.1c1.7,1.1,3.2,2.5,4.8,3.9c0.6,0.5,1.1,0.7,1.8,0.5
		c5.5-1.3,10.8-3.1,15.8-5.9c0.4-0.2,0.9-0.4,1.3-0.7c0.1,0,0.1,0,0.3,0.1c-2.2,6.6-6.3,11.7-12.1,15.5c0,0.1,0.1,0.2,0.1,0.3
		c1.7-0.3,3.5-0.5,5.2-0.9c1.8-0.4,3.5-0.9,5.3-1.5c1.7-0.6,3.4-1.2,5.3-1.7C218.2,106.7,216.9,108.4,215.6,110"/>
            </g>
            </svg> </a> <a href="https://plus.google.com/share?url=<?php echo $actual_link; ?>" title="Share on Google"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 282 282"  width="32px" height="32px" style="enable-background:new 0 0 282 282;" xml:space="preserve">
            <style type="text/css">
	.st0{clip-path:url(#SVGID_2_);}
</style>
            <g>
                <defs>
                    <rect id="SVGID_1_" width="282" height="282"/>
                </defs>
                <clipPath id="SVGID_2_">
                    <use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
                </clipPath>
                <path class="st0" d="M141,0C63.1,0,0,63.1,0,141s63.1,141,141,141s141-63.1,141-141S218.8,0,141,0 M184.4,165.5
		c-9.6,19.6-25.3,31.3-46.8,34.6c-19.4,3-36.5-2.8-50.6-16.4c-9.8-9.4-15.5-21.1-17.3-34.6c-0.2-1.4-0.4-2.8-0.6-4.2v-7.3
		c0.4-2.6,0.7-5.3,1.2-7.9c4.4-23.7,24-43.1,47.8-47.1c19.5-3.3,36.7,1.8,51.4,15.1c0.3,0.3,0.7,0.5,1.1,0.8c-4.6,5-9,9.8-13.5,14.6
		c-8.6-8.2-18.7-12.2-30.5-11.7c-8.5,0.4-16.2,3.4-22.8,8.8c-13.6,10.9-18.5,29.7-12,45.9c6.4,15.9,22.4,26.2,39.9,24.8
		c20.1-1.5,31.9-16.3,35-27.8H133v-18.4h55.4c0,0.5,0.1,1,0.1,1.5c0,3.5-0.2,7.1,0,10.6C189.1,153.5,187.3,159.6,184.4,165.5
		 M224.6,144.2h-11.3v11.5c-3.1,0-6.1,0-9-0.1c-0.3,0-0.7-0.7-0.7-1.1c-0.1-2.8,0-5.6,0-8.3v-1.8h-11.2v-9.7h11.2v-11.3h9.7v11.2
		h11.4V144.2z"/>
            </g>
            </svg> </a> <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($actual_link); ?>&title=<?php echo urlencode($this->item->title); ?>" title="Share on Linkedin"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 282 282"  width="32px" height="32px" style="enable-background:new 0 0 282 282;" xml:space="preserve">
            <style type="text/css">
	.st0{clip-path:url(#SVGID_2_);}
</style>
            <g>
                <defs>
                    <rect id="SVGID_1_" width="282" height="282"/>
                </defs>
                <clipPath id="SVGID_2_">
                    <use xlink:href="#SVGID_1_"  style="overflow:visible;"/>
                </clipPath>
                <path class="st0" d="M141,0C63.1,0,0,63.1,0,141c0,77.9,63.1,141,141,141s141-63.1,141-141C282,63.1,218.8,0,141,0 M107.4,190.2H80
		v-82.5h27.4V190.2z M96.2,96.1c-0.8,0.1-1.7,0.1-2.5,0.1c-11,0-18-9.4-14.4-19.4c2-5.5,7.4-9,14.5-9.2c7.1-0.2,13.1,3.8,15,10
		C111.4,86.6,105.6,95.2,96.2,96.1 M207.5,187.7v2.5h-27.7v-2.6c0-13.5,0.1-27-0.1-40.5c0-3.4-0.4-7-1.3-10.3
		c-3.3-11.9-16.6-11.2-22.5-5.8c-4.2,3.9-5.4,8.7-5.4,14.2c0.1,14.1,0,28.1,0,42.2v2.7H123v-82.4h27.5V119c0.7-0.8,1.1-1.2,1.4-1.6
		c5.5-7.7,13.1-11.6,22.5-11.8c10.3-0.2,19.4,2.8,25.8,11.4c4.5,6,6.5,12.9,6.9,20.2c0.4,7.1,0.3,14.2,0.4,21.3
		C207.5,168.3,207.5,178,207.5,187.7"/>
            </g>
            </svg> </a> </p>
    </div>
    </article>
</div>
