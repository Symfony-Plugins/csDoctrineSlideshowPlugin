<?php

/**
* 
*/
class sfWidgetFormJQuerySortableListMany extends sfWidgetFormJQuerySortableList
{
  public function addRelationsOptions($options)
  {
    foreach (Doctrine::getTable(get_class($options['object']))->getRelations() as $relation) 
    {
      if ($relation['alias'] == $options['hasMany']) 
      {
        $hasManyClass = $relation['class'];
        $refClass = str_replace('Table', '', get_class($relation['refTable']));
      }
    }

    $this->addOption('refClass', $refClass);
    $this->addOption('hasManyClass', $hasManyClass);
  }
  
  // Pulls default choices based on the passed options if none exist
  protected function getChoices($options)
  {
    return isset($options['choices']) ?  
        $options['choices'] : $this->collectionToArray(
            Doctrine::getTable($this->getOption('hasManyClass'))
              ->createQuery('s')
              ->leftJoin('s.'.$this->getOption('refClass').' ref')
              ->orderBy('ref.'.$this->getOption('position_field'))
              ->execute()
            );
  }
  
  // Returns all refClass objects currently associated with this object
  protected function getPositionObjects()
  {
    $object = $this->getOption('object');
    $selectedObjects = Doctrine::getTable($this->getOption('refClass'))
                              ->createQuery('s')
                              ->select('s.*, many.id as has_many_id')
                              ->innerJoin('s.'.get_class($object).' obj')
                              ->innerJoin('s.'.$this->getOption('hasManyClass').' many')                              
                              ->where('obj.id = ?', $object->id)       
                              ->orderBy('s.'.$this->getOption('position_field').' ASC')                                                    
                              ->execute();

    return $this->collectionToArray($selectedObjects, 'has_many_id');
  }
}
