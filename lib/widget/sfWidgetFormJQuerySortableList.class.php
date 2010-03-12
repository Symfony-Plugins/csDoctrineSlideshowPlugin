<?php

/**
* 
*/
class sfWidgetFormJQuerySortableList extends sfWidgetFormSelectCheckbox
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('hasMany');
    $this->addRequiredOption('object');
    
    $this->checkRequiredOptions($options);
    
    $this->addOption('method', '__toString');
    $this->addOption('position_field', 'position');   

    $this->addRelationsOptions($options);  
    
    $table = Doctrine_Inflector::tableize($options['hasMany']);
    $this->addOption('selected_list_id', 'selected_'.$table);
    $this->addOption('unselected_list_id', 'unselected_'.$table);
        
    $this->addOption('choices'); 
    
    // Parent options
    $this->addOption('class', 'sortable_list');
    $this->addOption('label_separator', '&nbsp;');
    $this->addOption('separator', "\n");
    $this->addOption('formatter', array($this, 'myFormatter'));
    $this->addOption('template', '%group% %options%');
  }
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfProjectConfiguration::getActive()->loadHelpers(array('JavascriptBase', 'Asset', 'Tag'));

    // As this relation isn't added by the form framework, find the active primary keys
    if ($value == null) 
    {
      $rel = $this->getOption('hasMany');
      $value = $this->getOption('object')->$rel->getPrimaryKeys();
    }

    $checkboxes = parent::render($name, $value, $attributes, $errors);
    $checkboxes = $this->getStyles().
                      $checkboxes.
                      javascript_tag($this->getJavascriptFunctions())

    ;
    return $checkboxes;
    
  }
  
  protected function formatChoices($name, $value, $choices, $attributes)
  {
    $selected_inputs = array();
    $unselected_inputs = array();
    $positionName = $this->generatePositionName($name);
    
    // refClass objects, used to determine the current sort order
    $objRefs = $this->getPositionObjects();

    // Separate selected and nonselect
    foreach ($choices as $key => $option)
    {
      if (in_array($key, $value))
      {
        $selected_inputs[] = $this->renderItemInput($name, $attributes, $key, $option, $positionName, $objRefs);
      }
      else
      {
        $unselected_inputs[] = $this->renderItemInput($name, $attributes, $key, $option, $positionName, false);
      }
    }

    $selected   = call_user_func($this->getOption('formatter'), $this, $selected_inputs, $this->getOption('selected_list_id'));
    $unselected = call_user_func($this->getOption('formatter'), $this, $unselected_inputs, $this->getOption('unselected_list_id'));    
    return $selected.$unselected;
  }
  
  // Renders individual item inputs
  protected function renderItemInput($name, $attributes, $key, $option, $positionName, $selected)
  {
    $method = $this->getOption('method');
    $positionCol = $this->getOption('position_field');
     
    $baseAttributes = array(
      'name'  => $this->generateValueName($name),
      'type'  => 'checkbox',
      'value' => self::escapeOnce($key),
      'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
      'onClick' => 'javascript:item_select(this)'
    );
    
    $positionAttributes = array(
      'class' => 'position',
      'type'  => 'hidden',
      'value' => '',
      'name'  => $positionName
      );

    if ($selected)
    {
      $baseAttributes['checked'] = 'checked';
      
      // add position value for selected attributes
      $positionAttributes['value'] = $selected[$key]->$positionCol; 
    }
    
    $temp = array(
      'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)).
                 $this->renderTag('input', $positionAttributes),
                 
      'label' => $this->renderContentTag('label', $option->$method(), array('for' => $id)),
    );
    
    return $temp;
  }
  
  public function myFormatter($widget, $inputs, $id)
  {
    $rows = array();
    $up = link_to_function('Up', 'javascript:up(this)', array('class' => 'position-controls'));
    $down = link_to_function('Down', 'javascript:down(this)', array('class' => 'position-controls'));
    foreach ($inputs as $input)
    {
      $item = $input['input'].$this->getOption('label_separator').$input['label'].$up.$down;
      $rows[] = $this->renderContentTag('li', $item);
    }

    return $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class'), 'id' => $id));
  }
  
  protected function getPositionObjects()
  {
    $object = $this->getOption('object');
    $selectedObjects = Doctrine::getTable($this->getOption('hasManyClass'))
                              ->createQuery('s')
                              ->select('s.*, obj.id as has_many_id')
                              ->innerJoin('s.'.get_class($object).' obj')
                              ->where('obj.id = ?', $object->id)   
                              ->orderBy('s.'.$this->getOption('position_field'))                          
                              ->execute();

    return $this->collectionToArray($selectedObjects);

  }

  public function addRelationsOptions($options)
  {
    foreach (Doctrine::getTable(get_class($options['object']))->getRelations() as $relation) 
    {
      if ($relation['alias'] == $options['hasMany']) 
      {
        $hasManyClass = $relation['class'];
      }
    }

    $this->addOption('hasManyClass', $hasManyClass);
  }
  public function checkRequiredOptions($options)
  {
    // check required options
    if ($diff = array_diff($this->requiredOptions, array_merge(array_keys($this->options), array_keys($options))))
    {
      throw new RuntimeException(sprintf('%s requires the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
    }
  }
  
  // Pulls default choices based on the passed options if none exist
  public function getChoices()
  {
    return $this->getOption('choices') ?  
        $this->getOption('choices') : $this->collectionToArray(
            Doctrine::getTable($this->getOption('hasManyClass'))
              ->createQuery('a')
              ->orderBy($this->getOption('position_field'))
              ->execute()
            );
  }
  
  public function collectionToArray($collection, $col='id')
  {
    $ret = array();
    foreach ($collection as $object) 
    {
      $ret[$object->$col] = $object;
    }
    return $ret;
  }
  
  public function getJavascriptFunctions()
  {
    $javascripts = <<<EOF
      function up(e)
      {
        var pos = parseInt($(e).parent().find('input.position:first').attr('value'));

        if(pos == 1) return;
        $("#%%selected_list_id%% input.position").each(function(e) {
          if ($(this).attr('value') == pos-1) {
            $(this).attr('value', pos);
          }
          else if($(this).attr('value') == pos)
          {
            $(this).attr('value', pos-1);
            $(this).parent().prev().before($(this).parent());
          }
        });
      }
      function down(e)
      {
        var pos = parseInt($(e).parent().find('input.position:first').attr('value'));
        var last = parseInt($("#%%selected_list_id%% input.position:last").attr('value'));
        if(pos == last) return;
        $("#%%selected_list_id%% input.position").each(function(e) {
          if ($(this).attr('value') == pos+1) {
            $(this).attr('value', pos);
          }
          else if($(this).attr('value') == pos)
          {
            $(this).attr('value', pos+1);
            $(this).parent().next().after($(this).parent());
          }
        });
      }
      function item_select(e)
      {
        if ($(e).attr('checked')) 
        {
          var last_object = $("#%%selected_list_id%% input.position:last");
          var last = parseInt(last_object.attr('value'));
          last = last ? last : 0;
          $("#%%selected_list_id%%").append($(e).parent()); 
          $(e).parent().find('input.position').attr('value', last+1);
        }
        else
        {
          var pos = parseInt($(e).parent().find('input.position').attr('value'));
          $("#%%selected_list_id%% input.position").each(function(e) {
            if ($(this).attr('value') > pos) {
              $(this).attr('value', parseInt($(this).attr('value'))-1);
            }
          });    

          $("#%%unselected_list_id%%").append($(e).parent());      
          $(e).parent().find('input.position').attr('value', '');    
        }
      }
EOF;

    return strtr($javascripts, array(
              '%%selected_list_id%%' => $this->getOption('selected_list_id'), 
              '%%unselected_list_id%%' => $this->getOption('unselected_list_id')
              ));
  }
  public function getStyles()
  {
    $styles = <<<EOF
<style type='text/stylesheet'>
  #%%unselected_list_id%% .position-controls
  {
    display:none;
  }
</style>
EOF;

    return strtr($styles, array(
        '%%unselected_list_id%%' => $this->getOption('unselected_list_id'), 
        ));
  }
  
  public function generatePositionName($name)
  {
    $strippedName = substr($name, 0, -2);
    return $strippedName.'[position][]';
  }
  public function generateValueName($name)
  {
    $strippedName = substr($name, 0, -2);
    return $strippedName.'[value][]';
  }

  public function getJavascripts()
  {
    return array('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js');
  }
}
