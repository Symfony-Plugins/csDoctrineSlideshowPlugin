<?php

/**
* Parses an options string into a form object
*/
class sfSlideshowOptionsForm extends sfFormDoctrine
{
  public $renderer;
  
  public function __construct($defaults, $object = null, $options = array(), $CSRFSecret = null)
  {
    if(!is_array($defaults))
    {
      $defaults = csSlideshowToolkit::stringToArray($defaults);
    }
    $this->option_defaults = $defaults;
    return parent::__construct($object, $options, $CSRFSecret);
  }
  
  public function setup()
  {
    parent::setup();
    $rendererClass = $this->getRendererClass();
    $this->renderer = new $rendererClass();
    
    // Creates widgest based on the options and their available choices
    if (!$this->option_defaults) 
    {
      $this->option_defaults = $this->renderer->options;
    }
    foreach ($this->option_defaults as $field => $default) 
    {
      if (isset($this->renderer->$field)) 
      {
        $choices = $this->getDefaultChoices($this->renderer->$field);
        $this->widgetSchema[$field] = new sfWidgetFormChoice(
                                                array('choices' => $choices));
      }
      else
      {
        $this->widgetSchema[$field] = new sfWidgetFormInput();
        $this->validatorSchema[$field] = new sfValidatorString(array('required' => false));
      }
    }
    $this->setDefaults($this->option_defaults);
  }
  public function getRendererClass()
  {
    // this method can be overridden
    return $this->object->renderer;
  }
  public function getDefaultChoices($choices)
  {
    $defaults = array();
    foreach ($choices as $choice) 
    {
      $defaults[$choice] = $choice;
    }
    return $defaults;
  }
  public function getModelName()
  {
    return 'Slideshow';
  }
  
  /**
   * converts an array to a parsable string
   *
   * @param string $optionsArray 
   * @return void
   * @author Brent Shaffer
   */
  public function getFlattenedValues($optionsArray)
  {
    $options = '';
    foreach ($optionsArray as $field => $value) 
    {
      $options .= "$field=$value\n";
    }
    return $options;
  }
}
