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
             $optionsForm = new $optionsClass($this->object->options, $this->object);
           }
           else
           {
             $optionsForm = new sfSlideshowOptionsForm($this->object->options, $this->object);
           }
           $this->embedForm('options', $optionsForm);
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
   public function configure()
   {
     // $this->setSlidesListWidget();
   }
   public function setSlidesListWidget()
   {
     $this->widgetSchema['slides_list'] = new sfWidgetFormDoctrineChoiceMany(array('model' => 'Slide', 'expanded' => true));
     $this->validatorSchema['slides_list'] = new sfValidatorDoctrineChoiceMany(array('model' => 'Slide', 'required' => false));
   }
   public function getRenderer()
   {
     $class = $this->object->renderer;
       return new $class();
   }
   public function bind(array $taintedValues = null, array $taintedFiles = null)
   {
       //Do Special binding for slideshow options if options exist
       if (array_key_exists('options', $taintedValues) && array_key_exists('options', $this->embeddedForms)) 
       {
         //renderer is changed, pull in default rendering options
         if ($taintedValues['renderer'] != $this->object->renderer) 
         {
         $class = $taintedValues['renderer'];
           $renderer = new $class();
           $taintedValues['options'] = $renderer->getDefaultOptions();
         }
         else
         {
           // Flattens the option form into a string
           $taintedValues['options'] = $this->embeddedForms['options']->getFlattenedValues($taintedValues['options']);
         }
         // replaces embedded form with a string widget
         unset($this['options']);
         $this->widgetSchema['options'] = new sfWidgetFormInput();
         $this->validatorSchema['options'] = new sfValidatorString();
       }
       return parent::bind($taintedValues, $taintedFiles);
     }
}