<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Version;

HTMLHelper::_('bootstrap.tooltip');

if (Version::MAJOR_VERSION < 4) {
    HTMLHelper::_('behavior.formvalidation');
    HTMLHelper::_('formbehavior.chosen', 'select');

    Factory::getDocument()->addScriptDeclaration("
        Joomla.submitbutton = function (task) {

            var msg_dest = '', msg_valid = '';

            var ds = document.getElementById('jform_destsystem0').checked;
            var dn = document.getElementById('jform_destmenu0').checked;
            var du = document.getElementById('jform_destusers0').checked;
            var dn = document.getElementById('jform_destcontacts0').checked;
            var da = document.getElementById('jform_destarticles0').checked;
            var dc = document.getElementById('jform_destcategories0').checked;
            var dm = document.getElementById('jform_destmodules0').checked;
            var dp = document.getElementById('jform_destplugins0').checked;
            var df = document.getElementById('jform_destfields0').checked;
            var dt = document.getElementById('jform_desttags0').checked;

            var dest = ds || dn || du || dn || da || dc || dm || dp || df || dt;

            var valid = document.formvalidator.isValid(document.id('item-form'));

            if (task == 'item.cancel' || (dest && valid)) {
                Joomla.submitform(task, document.getElementById('item-form'));
            } else {
                if (!dest) {
                    msg_dest = '" . $this->escape(Text::_('COM_ATTRS_DEST_ERROR')) . "';
                }
                if (!valid) {
                    msg_valid = '" . $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')) . "';
                }

                Joomla.JText.load({error:'" . $this->escape(Text::_('ERROR')) . "'});
                Joomla.renderMessages({'error':[msg_valid, msg_dest]});
            }
        }
    ");
} else {
    Factory::getDocument()->addScriptDeclaration("
        Joomla.submitbutton = function (task) {

            var msg_dest = '', msg_valid = '';

            var ds = document.getElementById('jform_destsystem0').checked;
            var dn = document.getElementById('jform_destmenu0').checked;
            var du = document.getElementById('jform_destusers0').checked;
            var dn = document.getElementById('jform_destcontacts0').checked;
            var da = document.getElementById('jform_destarticles0').checked;
            var dc = document.getElementById('jform_destcategories0').checked;
            var dm = document.getElementById('jform_destmodules0').checked;
            var dp = document.getElementById('jform_destplugins0').checked;
            var df = document.getElementById('jform_destfields0').checked;
            var dt = document.getElementById('jform_desttags0').checked;

            var dest = ds || dn || du || dn || da || dc || dm || dp || df || dt;

            if (task == 'item.cancel' || dest) {
                Joomla.submitform(task, document.getElementById('item-form'));
            } else {
                if (!dest) {
                    msg_dest = '" . $this->escape(Text::_('COM_ATTRS_DEST_ERROR')) . "';
                }

                Joomla.JText.load({error:'" . $this->escape(Text::_('ERROR')) . "'});
                Joomla.renderMessages({'error':[msg_dest]});
            }
        }
    ");
}
?>

<form action="<?php echo Route::_('index.php?option=com_attrs&layout=edit&id=' . $this->form->getValue('id')); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <div class="row<?php echo Version::MAJOR_VERSION < 4 ? '-fluid' : ''; ?>">
            <div class="span6 col-sm">
                <div class="form-vertical">

                    <?php echo $this->form->renderField('title'); ?>

                    <?php echo $this->form->renderField('name'); ?>

                    <?php echo $this->form->renderField('tp'); ?>

                    <?php echo $this->form->renderField('val'); ?>

                    <?php echo $this->form->renderField('multiple'); ?>

                    <?php echo $this->form->renderField('filter'); ?>

                    <?php echo $this->form->renderField('class'); ?>

                    <?php echo $this->form->renderField('layout'); ?>

                    <hr>

                    <?php echo $this->form->renderField('published'); ?>

                    <?php echo $this->form->renderField('id'); ?>

                </div>
            </div>
            <div class="span6 col-sm">
                <div class="form-horizontal">

                    <?php echo $this->form->renderField('destsystem'); ?>

                    <?php echo $this->form->renderField('destmenu'); ?>

                    <?php echo $this->form->renderField('destusers'); ?>

                    <?php echo $this->form->renderField('destcontacts'); ?>

                    <?php echo $this->form->renderField('destarticles'); ?>

                    <?php echo $this->form->renderField('destcategories'); ?>

                    <?php echo $this->form->renderField('destmodules'); ?>

                    <?php echo $this->form->renderField('destplugins'); ?>

                    <?php echo $this->form->renderField('destfields'); ?>

                    <?php echo $this->form->renderField('desttags'); ?>

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="return" value="<?php echo Factory::getApplication()->input->getCmd('return'); ?>" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<style>
.form-horizontal .icon-stack {
    height: auto;
    width: auto;
    line-height: initial;
}
</style>
