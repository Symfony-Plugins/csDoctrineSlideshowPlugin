<?php

/**
 * PluginSlideshow form.
 *
 * @package    form
 * @subpackage Slideshow
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginSlideshowForm extends BaseSlideshowForm
{
	public function setUp()
	{
		parent::setUp();
		unset($this['created_at'], $this['updated_at']);
		if ($this->object->renderer)
		{
		  $renderer = $this->getRenderer();
		  if ($renderer->options) 
		  {
        $optionsClass = $this->object->renderer . 'OptionsForm';
        if (class_exists($optionsClass)) 
        {
          $this->embedForm('options', $optionsForm);
        }
		  }
		  else
		  {
		    unset($this['options']);
		  }
		}
    $this->widgetSchema['renderer'] = new sfWidgetFormChoice(array(
                                        'choices' => sfConfig::get('app_slideshow_renderers')
                                        ));
	}
	public function getRenderer()
	{
	  $class = $this->object->renderer;
    return new $class();
	}
	public function bind(array $taintedValues = null, array $taintedFiles = null)
	{
    //Do Special binding for slideshow options if options exist
    if (array_key_exists('options', $taintedValues)) 
    {
      // Flattens the option form into a string
      $taintedValues['options'] = $this->embeddedForms['options']->getFlattenedValues($taintedValues['options']);
      unset($this['options']);
      $this->widgetSchema['options'] = new sfWidgetFormInput();
      $this->validatorSchema['options'] = new sfValidatorString();
    }
    return parent::bind($taintedValues, $taintedFiles);
  }
}