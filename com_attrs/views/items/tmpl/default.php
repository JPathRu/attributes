<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo Route::_('index.php?option=com_attrs&view=items'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		
		<?php
		if (empty($this->items)) {
			if ($this->allCount != 0) {
				echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
		?>
		<div class="alert alert-no-items">
			<?php echo Text::_('COM_ATTRS_DATA_EMPTY_FROM_FILTER'); ?>
		</div>
		<?php
			} else {
		?>
		<div class="alert alert-no-items">
			<?php echo Text::_('COM_ATTRS_DATA_EMPTY'); ?>
		</div>
		<?php
			}
		} else {
			echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
		?>
			
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone center"><?php echo HTMLHelper::_('grid.checkall'); ?></th>
						<th width="5%" class="center" style="min-width:55px;"><?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?></th>
						<th class="has-context"><?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?></th>
						<th width="10%"><?php echo Text::_('COM_ATTRS_TYPE'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_SYSTEM'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_MENU'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_ARTICLES'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_CATEGORIES'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_MODULES'); ?></th>
						<th width="5%" class="hidden-phone center nowrap"><?php echo Text::_('COM_ATTRS_DEST_PLUGINS'); ?></th>
						<th width="1%" class="hidden-phone center nowrap"><?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->items as $i => $item) {
					?>
					<tr class="row<?php echo $i % 2; ?>">
						
						<td class="center hidden-phone">
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						
						<td class="center">
							<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'items.', $this->canDo, 'cb', null, null); ?>
						</td>
						
						<td class="has-context nowrap">
							<?php if ($this->canDo) { ?>
							<a href="<?php echo Route::_('index.php?option=com_attrs&task=item.edit&id=' . $item->id); ?>">
							<?php } ?>
							
							<?php echo $this->escape($item->title); ?>
							
							<?php if ($this->canDo) { ?>
							</a>
							<?php } ?>
							
							<div class="small"><?php echo $this->escape($item->name); ?></div>
						</td>
						
						<td class="nowrap">
							<strong><?php echo Text::_(mb_strtoupper('COM_ATTRS_TYPE_' . $item->tp)); ?></strong>
							
							<?php if ($item->filter) { ?>
							<div class="small"><?php echo Text::_('COM_ATTRS_VAL_FILTER') . ': ' . $this->escape($item->filter); ?></div>
							<?php } ?>
							
							<?php if ($item->multiple) { ?>
							<div class="small"><?php echo Text::_('COM_ATTRS_VAL_MULTIPLE'); ?></div>
							<?php } ?>
						</td>
						
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destsystem ? '' : 'un'); ?>publish"></span></td>
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destmenu ? '' : 'un'); ?>publish"></span></td>
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destarticles ? '' : 'un'); ?>publish"></span></td>
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destcategories ? '' : 'un'); ?>publish"></span></td>
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destmodules ? '' : 'un'); ?>publish"></span></td>
						<td class="center hidden-phone"><span class="icon-<?php echo ($item->destplugins ? '' : 'un'); ?>publish"></span></td>
						
						<td class="center hidden-phone"><?php echo $item->id; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
				
			<?php echo $this->pagination->getListFooter(); ?>
				
		<?php } ?>
		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
		
	</div>
</form>