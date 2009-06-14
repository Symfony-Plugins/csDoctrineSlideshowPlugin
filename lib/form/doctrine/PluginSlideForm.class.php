<?php

/**
 * PluginSlide form.
 *
 * @package    form
 * @subpackage Slide
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginSlideForm extends BaseSlideForm
{
	public function setUp()
	{
		parent::setUp();
		unset($this['created_at'], $this['updated_at']);
		$this->setImageField();
	}
	public function setImageField($path = null, $field = 'image')
	{
		$upload_path = $path ? $path : $this->getObject()->getUploadPath();
		sfProjectConfiguration::getActive()->loadHelpers('Asset','Url','Tag');
		$this->widgetSchema[$field] = new sfWidgetFormInputFileEditable(array(
                                         'label'     => 'Image',
                                         'file_src'  => $path ? $path .'/'.$this->getObject()->getImage() : image_path($this->getObject()->getImagepath()),
                                         'is_image'  => true,
                                         'edit_mode' => !$this->isNew() && $this->getObject()->getImage(),
                                         'template'  => '<div style="width: 300px; margin-left: 160px;">%file%<br /><br />%input%<br />%delete% %delete_label%</div>'));
		
		$this->validatorSchema[$field]	 = new sfValidatorFile(array(
															'required'       => false,
                              'max_size'       => 2048000,
                                // 'mime_types'     => $mimetypes,
                              'path'          => $upload_path
									));
									
		$this->validatorSchema[$field.'_delete'] = new sfValidatorBoolean();
	}
}