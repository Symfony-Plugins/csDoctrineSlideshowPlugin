<?php

/**
* 
*/
class sfValidatorSortableList extends sfValidatorDoctrineChoice
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('position_field', 'position');
    parent::configure($options, $messages);
    $this->setOption('multiple', true);
  }
  
  protected function doClean($value)
  {
    if (!isset($value['value'])) 
    {
      return array();
    }
    $value['value'] = parent::doClean($value['value']);
    return array_combine($value['value'], array_filter($value['position']));
  }
}
