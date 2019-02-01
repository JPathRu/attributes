<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		
		var msg_sn = '', msg_dest = '', msg_valid = '';
		
		var sn = /^[a-z_]+$/.test(document.getElementById('jform_name').value);
		
		var ds = document.getElementById('jform_destsystem0').checked;
		var dn = document.getElementById('jform_destmenu0').checked;
		var da = document.getElementById('jform_destarticles0').checked;
		var dc = document.getElementById('jform_destcategories0').checked;
		var dm = document.getElementById('jform_destmodules0').checked;
		var dp = document.getElementById('jform_destplugins0').checked;
		
		var dest = ds || dn || da || dc || dm || dp;
		
		var valid = document.formvalidator.isValid(document.id('item-form'));
		
		if (task == 'item.cancel' || (sn && dest && valid)) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			if (!sn) {
				msg_sn = "<?php echo $this->escape(Text::_('COM_ATTRS_NAME_ERROR')); ?>";
			}
			if (!dest) {
				msg_dest = "<?php echo $this->escape(Text::_('COM_ATTRS_DEST_ERROR')); ?>";
			}
			if (!valid) {
				msg_valid = "<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>";
			}
			
			Joomla.JText.load({error:"<?php echo $this->escape(Text::_('ERROR')); ?>"});
			Joomla.renderMessages({"error":[msg_sn, msg_dest, msg_valid]});
		}
	}
</script>
<form action="<?php echo Route::_('index.php?option=com_attrs&layout=edit&id=' . $this->form->getValue('id')); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
	
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span6">
				<div class="form-vertical">
				
					<?php echo $this->form->renderField('title'); ?>

					<?php echo $this->form->renderField('name'); ?>

					<?php echo $this->form->renderField('tp'); ?>

					<?php echo $this->form->renderField('val'); ?>
					
					<?php echo $this->form->renderField('multiple'); ?>
					
					<?php echo $this->form->renderField('filter'); ?>
					
					<?php echo $this->form->renderField('class'); ?>

				</div>
			</div>
			<div class="span3">
				<div class="form-horizontal">
					
					<?php echo $this->form->renderField('destsystem'); ?>
					
					<?php echo $this->form->renderField('destmenu'); ?>
					
					<?php echo $this->form->renderField('destarticles'); ?>
					
					<?php echo $this->form->renderField('destcategories'); ?>
					
					<?php echo $this->form->renderField('destmodules'); ?>
					
					<?php echo $this->form->renderField('destplugins'); ?>
					
				</div>
			</div>
			<div class="span3">
				<div class="form-vertical">
					
					<?php echo $this->form->renderField('published'); ?>
					
					<?php echo $this->form->renderField('id'); ?>
					
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo Factory::getApplication()->input->getCmd('return'); ?>" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>