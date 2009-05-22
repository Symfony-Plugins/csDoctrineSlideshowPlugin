<?php

/**
* Parses an options string into a form object
*/
abstract class sfSlideshowOptionsForm extends sfOptionsForm
{
  public $renderer;
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
        $this->widgetSchema[$field] = new sfWidgetFormChoice(
                                                array('choices' => $this->renderer->$field));
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
    // Guesses the renderer class based on the name of the options form
    // If classes follow naming conventions, this will work.  Otherwise,
    // this method can be overridden
    return str_replace('OptionsForm', '', get_class($this));
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
