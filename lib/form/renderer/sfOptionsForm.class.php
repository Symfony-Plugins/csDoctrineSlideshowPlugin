<?php

/**
* Parses an options string into a form object
*/
abstract class sfOptionsForm extends sfFormDoctrine
{
  public function __construct($defaults, $object = null, $options = array(), $CSRFSecret = null)
  {
    if(!is_array($defaults))
    {
      $defaults = sfToolkit::stringToArray($defaults);
    }
    $this->option_defaults = $defaults;
    return parent::__construct($object, $options, $CSRFSecret);
  }
  public function setup()
  {
    foreach ($this->option_defaults as $field => $default) 
    {
      $this->widgetSchema[$field] = new sfWidgetFormInput();
      $this->validatorSchema[$field] = new sfValidatorString(array('required' => false));
    }
    $this->setDefaults($this->option_defaults);
  }
}
