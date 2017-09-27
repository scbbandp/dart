<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Page
 * @author     Simon Cruise <simon.cruise@bbandp.com>
 * @copyright  2017 Simon Cruise
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * 
 * var $this->form
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_page/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'tpage.cancel') {
			Joomla.submitform(task, document.getElementById('tpage-form'));
		}
		else {
			
			if (task != 'tpage.cancel' && document.formvalidator.isValid(document.id('tpage-form'))) {
				
				Joomla.submitform(task, document.getElementById('tpage-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>
<?php $count = 0; ?>
<form
	action="<?php echo JRoute::_('index.php?option=com_page&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="tpage-form" class="form-validate">
    <div class="form-horizontal"> 
    
    	<?php echo $this->form->renderField('name'); ?>
    
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'page')); ?>
    	
    	
    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PAGE_TITLE_TPAGE', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">
                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
                    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
                    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
                    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
                    <input type="hidden" name="jform[tester]" value="HOWDY" />
                    <?php echo $this->form->renderField('created_by'); ?>
					<?php echo $this->form->renderField('modified_by'); ?>
					
                    <?php echo $this->form->renderField('title'); ?>
                    <?php echo $this->form->renderField('intro'); ?>
                    
                    <?php echo $this->form->renderField('intro_image'); ?>
                    <?php echo $this->form->renderField('module'); ?>
                    <?php echo $this->form->renderField('module_name'); ?>
					<?php //echo $this->form->renderField('content'); ?>
					<?php //echo $this->form->renderField('alias'); ?>
                    <?php if ($this->state->params->get('save_history', 1)) : ?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
                    </div>
                    <?php endif; ?>
                </fieldset>
                
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
    	
    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'page', 'Content'); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal" id="block-forms">
            	
                <?php //$this->item->content = json_decode($this->item->content); ?>
                <?php if($this->item->content):?>
                	
	                <?php foreach($this->item->content as $block):?>
	                <?php 
	                /*
	                 * #TODO render form elements using JFormFields
	                    $media = new JFormFieldMedia();
	                    $media->setForm($this->form);
	                    $media->name = "jform[blocks][{$count}][image]";
	                    $media->setValue($block->image);
	                    $media->id = "blocks_{$count}_image";
	                    $media->label = 'Image';
	                    echo $media->renderField(array());    
		              */?>
	                
		                <fieldset id="block-<?php echo $count; ?>">
		                    <h2><?php echo $block->type; ?> block</h2>
                            
                            <div class="btn-wrapper" id="toolbar-trash">
                                <button onclick="deleteMe('block-<?php echo $count; ?>');" class="btn btn-small">
                                <span class="icon-trash"></span>
                                Trash</button>
                            </div>
                            
		                    <input type="hidden" name="jform[blocks][<?php echo $count; ?>][type]"  value="<?php echo $block->type; ?>" />
		                    
		                   
		                    <div class="control-group">
		                        <div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_title">Name</label>
		                        </div>
		                        <div class="controls">
		                            <input value="<?php echo $block->title; ?>" type="text" name="jform[blocks][<?php echo $count; ?>][title]" id="jform_blocks_<?php echo $count; ?>_title" value="" placeholder="Title">
		                        </div>
		                    </div>
		                   	
		                    <?php if($block->type != 'module' && $block->type != 'cta'): ?>
		                    <div class="control-group">
							    <div class="control-label">
							        <label id="jform_intro_<?php echo $count; ?>_image-lbl" for="jform_intro_<?php echo $count; ?>_image">Image</label>
							    </div>
							    <div class="controls">
							        <div class="field-media-wrapper" data-basepath="<?php echo JUri::root() ?>" data-url="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;&amp;&amp;fieldid={field-media-id}&amp;ismoo=0&amp;folder=" data-modal=".modal" data-modal-width="100%" data-modal-height="400px" data-input=".field-media-input" data-button-select=".button-select" data-button-clear=".button-clear" data-button-save-selected=".button-save-selected" data-preview="true" data-preview-as-tooltip="true" data-preview-container=".field-media-preview" data-preview-width="200" data-preview-height="200">
							            <div id="imageModal_jform_intro_<?php echo $count; ?>_image" tabindex="-1" class="modal hide fade">
							                <div class="modal-header">
							                    <button type="button" class="close novalidate" data-dismiss="modal">x</button>
							                    <h3>Change Image</h3>
							                </div>
							                <div class="modal-body"> </div>
							                <div class="modal-footer"> <a class="btn" data-dismiss="modal">Cancel</a></div>
							            </div>
							            <div class="input-prepend input-append"> <span rel="popover" class="add-on pop-helper field-media-preview" title="" data-content="No image selected." data-original-title="Selected image." data-trigger="hover"> <span class="icon-eye" aria-hidden="true"></span> </span>
							                <input type="text" name="jform[blocks][<?php echo $count; ?>][image]" id="jform_intro_<?php echo $count; ?>_image" value="<?php echo $block->image; ?>" readonly="readonly" class="input-small hasTooltip field-media-input" data-original-title="" title="">
							                <a class="btn add-on button-select"  data-target="#imageModal_jform_intro_<?php echo $count; ?>_image">Select</a> <a class="btn icon-remove hasTooltip add-on button-clear" title="" data-original-title="Clear"></a> </div>
							        </div>
							    </div>
							</div>
                            
                            <div class="control-group">
		                        <div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_ns">No shadow</label>
		                        </div>
		                        <div class="controls">
                                <?php $checked = isset($block->ns) && $block->ns ? 'checked="checked"' : ''; ?>
                                <input <?php echo $checked; ?> id="jform_blocks_<?php echo $count; ?>_ns" type="checkbox" value="y" name="jform[blocks][<?php echo $count; ?>][ns]">

		                        </div>
		                    </div>
                            
		                    <?php endif; ?>
		                 	<?php if($block->type == 'module'): ?>
                            	<div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_text">Id</label>
		                        </div>
		                        <div class="controls">
		                            <input value="<?php echo $block->text; ?>" type="text" name="jform[blocks][<?php echo $count; ?>][text]" id="jform_blocks_<?php echo $count; ?>_text" value="" placeholder="Id">
		                        </div>
                            <?php else: ?>
		                    <div class="control-group">
		                        <div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_text"> Text</label>
		                        </div>
		                        <div class="controls">
		                            <textarea class="content-textarea" name="jform[blocks][<?php echo $count; ?>][text]" id="jform_blocks_<?php echo $count; ?>_text" placeholder="Text"><?php echo $block->text; ?></textarea>
		                        </div>
		                    </div>
		                    <?php endif; ?>
		                    <?php if($block->type != 'quote' && $block->type != 'module'): ?>
		                    <div class="control-group">
		                        <div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_href">HREF</label>
		                        </div>
		                        <div class="controls">
		                            <input value="<?php echo $block->href; ?>" type="text" name="jform[blocks][<?php echo $count; ?>][href]" id="jform_blocks_<?php echo $count; ?>_href" value="" placeholder="HREF">
		                        </div>
		                    </div>
		                    
		                    
		                    <div class="control-group">
		                        <div class="control-label">
		                            <label for="jform_blocks_<?php echo $count; ?>_cta">Link text</label>
		                        </div>
		                        <div class="controls">
		                            <input value="<?php echo $block->cta; ?>" type="text" name="jform[blocks][<?php echo $count; ?>][cta]" id="jform_blocks_<?php echo $count; ?>_cta" value="" placeholder="Link text">
		                        </div>
		                    </div>
		                     <?php endif; ?>
		                     
		                     <?php if($block->type == 'quote'): ?>
                                 <div class="control-group">
                                    <div class="control-label">
                                        <label for="jform_blocks_<?php echo $count; ?>_quote"> Quote</label>
                                    </div>
                                    <div class="controls">
                                        <textarea name="jform[blocks][<?php echo $count; ?>][quote]" id="jform_blocks_<?php echo $count; ?>_quote" placeholder="Quote"><?php echo $block->quote; ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <div class="control-label">
                                        <label for="jform_blocks_<?php echo $count; ?>_author">Author</label>
                                    </div>
                                    <div class="controls">
                                        <textarea name="jform[blocks][<?php echo $count; ?>][author]" id="jform_blocks_<?php echo $count; ?>_author" placeholder="Author"><?php echo $block->author; ?></textarea>
                                    </div>
                                </div>
		                     <?php endif; ?>
		                </fieldset>
		                <?php $count++; ?>
	                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="clearfix btn-toolbar">
			<!--<div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="hero" title="" data-original-title="Add block">Add hero block</button>
			</div>-->
			<div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="content" title="" data-original-title="Add block">Add content block</button>
			</div>
            <div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="quote" title="" data-original-title="Add block">Add content block with quote</button>
			</div>
			<div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="cta" title="" data-original-title="Add block">Add CTA block</button>
			</div>
			<div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="blue" title="" data-original-title="Add block">Add blue block</button>
			</div>
			<div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="module" title="" data-original-title="Add block">Add module block</button>
			</div>
            <div class="btn-wrapper">
				<button type="button" class="btn add-block" data-type="stack" title="" data-original-title="Add block">Add stack block</button>
			</div>
		</div>
        
        <?php echo JHtml::_('bootstrap.endTab'); ?> 
        
        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?> </div>
</form>



<script>
	var blockCount = <?php echo $count; ?>;
	jQuery(document).ready(function(e) {
		
        jQuery(".add-block").click(function() {
        	addBlock(jQuery(this).data('type'));
        });
    });

	function addBlock(type){
		
		var html = '';
        html += '<fieldset id="block-'  + blockCount + '">';
        html += '<h2>' + type +' block</h2>';
		
		html += '<div class="btn-wrapper" id="toolbar-trash">';
		html += '	<button onclick="deleteMe(\'block-' + blockCount + '\');" class="btn btn-small">';
		html += '	<span class="icon-trash"></span>';
		html += '	Trash</button>';
		html += '</div>';
		
        html += '<input type="hidden" name="jform[blocks]['  + blockCount + '][type]"  value="'  + type + '" />';
        
        html += '<div class="control-group">';
        html += '    <div class="control-label">';
        html += '        <label for="jform_blocks'  + blockCount + '_title"> Name</label>';
        html += '     </div>';
        html += '    <div class="controls">';
        html += '        <input type="text" name="jform[blocks]['  + blockCount + '][title]" id="jform_blocks_'  + blockCount + '_title" value="" placeholder="Title">';
        html += '    </div>';
        html += '</div>';

        if(type != 'module'){
	        if(type != 'cta') {
		        var img_in = jQuery('#clone_image_html').prop('outerHTML');
		        html += img_in.replace(/%id%/g, blockCount);
	        }
	        
	        html += '<div class="control-group">';
	        html += '    <div class="control-label">';
	        html += '        <label for="jform_blocks_'  + blockCount + '_text"> Text</label>';
	        html += '    </div>';
	        html += '    <div class="controls">';
	        html += '        <textarea name="jform[blocks]['  + blockCount + '][text]" id="jform_blocks_'  + blockCount + '_text" placeholder="Text"></textarea>';
	        html += '    </div>';
	        html += '</div>';
	
	        if(type != 'quote') {
		        html += '<div class="control-group">';
		        html += '    <div class="control-label">';
		        html += '        <label for="jform_blocks'  + blockCount + '_href">HREF</label>';
		        html += '     </div>';
		        html += '    <div class="controls">';
		        html += '        <input type="text" name="jform[blocks]['  + blockCount + '][href]" id="jform_blocks_'  + blockCount + '_href" value="" placeholder="HREF">';
		        html += '    </div>';
		        html += '</div>';
		
		        html += '<div class="control-group">';
		        html += '    <div class="control-label">';
		        html += '        <label for="jform_blocks'  + blockCount + '_cta"> Link text</label>';
		        html += '     </div>';
		        html += '    <div class="controls">';
		        html += '        <input type="text" name="jform[blocks]['  + blockCount + '][cta]" id="jform_blocks_'  + blockCount + '_cta" value="" placeholder="Link text">';
		        html += '    </div>';
		        html += '</div>';
	        }
        }
		
		if(type=='quote') {
			html += '<div class="control-group">';
			html += '	<div class="control-label">';
			html += '		<label for="jform_blocks_'  + blockCount + '_quote"> Quote</label>';
			html += '	</div>';
			html += '	<div class="controls">';
			html += '		<textarea name="jform[blocks]['  + blockCount + '][quote]" id="jform_blocks_'  + blockCount + '_quote" placeholder="Quote"></textarea>';
			html += '	</div>';
			html += '</div>';
			
			html += '<div class="control-group">';
			html += '	<div class="control-label">';
			html += '		<label for="jform_blocks_'  + blockCount + '_author">Author</label>';
			html += '	</div>';
			html += '	<div class="controls">';
			html += '		<textarea name="jform[blocks]['  + blockCount + '][author]" id="jform_blocks_'  + blockCount + '_author" placeholder="Author"></textarea>';
			html += '	</div>';
			html += '</div>';
		}
        
        html += '</fieldset>';
        jQuery("#block-forms").append(html);
        jQuery('.field-media-wrapper').fieldMedia();
		
		initEditor('#jform_blocks_'  + blockCount + '_text');
		
        blockCount++;
	}
    function deleteMe(id) {
		if(confirm("Are you sure you want to delete this?")){
			jQuery("#" + id).remove();
		}
		
		return false;
	}
	
	function initEditor(id) {
		tinymce.init({
		  selector: id,
		  height: 500,
		  menubar: false,
		  fontsize_formats: "16px 18px",
		  plugins: [
			'advlist lists link charmap anchor image',
			'searchreplace visualblocks code fullscreen ',
			'insertdatetime media table contextmenu paste code textcolor colorpicker textpattern hr '
		  ],
		  toolbar: 'undo redo | insert | styleselect | forecolor backcolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr image fontsizeselect',
		});
	}
	
	initEditor('.content-textarea');
	
</script>
<div style="display:none;">
	<div class="control-group" id="clone_image_html">
	    <div class="control-label">
	        <label id="jform_intro_%id%_image-lbl" for="jform_intro_%id%_image">Image</label>
	    </div>
	    <div class="controls">
	        <div class="field-media-wrapper" data-basepath="<?php echo JUri::root() ?>" data-url="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;&amp;&amp;fieldid={field-media-id}&amp;ismoo=0&amp;folder=" data-modal=".modal" data-modal-width="100%" data-modal-height="400px" data-input=".field-media-input" data-button-select=".button-select" data-button-clear=".button-clear" data-button-save-selected=".button-save-selected" data-preview="true" data-preview-as-tooltip="true" data-preview-container=".field-media-preview" data-preview-width="200" data-preview-height="200">
	            <div id="imageModal_jform_intro_%id%_image" tabindex="-1" class="modal hide fade">
	                <div class="modal-header">
	                    <button type="button" class="close novalidate" data-dismiss="modal">x</button>
	                    <h3>Change Image</h3>
	                </div>
	                <div class="modal-body"> </div>
	                <div class="modal-footer"> <a class="btn" data-dismiss="modal">Cancel</a></div>
	            </div>
	            <div class="input-prepend input-append"> <span rel="popover" class="add-on pop-helper field-media-preview" title="" data-content="No image selected." data-original-title="Selected image." data-trigger="hover"> <span class="icon-eye" aria-hidden="true"></span> </span>
	                <input type="text" name="jform[blocks][%id%][image]" id="jform_intro_%id%_image" value="" readonly="readonly" class="input-small hasTooltip field-media-input" data-original-title="" title="">
	                <a class="btn add-on button-select" id="btn_%id%" data-target="#imageModal_jform_intro_%id%_image">Select</a> <a class="btn icon-remove hasTooltip add-on button-clear" title="" data-original-title="Clear"></a> </div>
	        </div>
	    </div>
	</div>
</div>
