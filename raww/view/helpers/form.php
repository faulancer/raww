<?php
  
  class FormHelper{
   
   
  /**
  * ...
  *
  * @return ?
  */
   	public static function input($data, $value = '', $extra = ''){
		
      if( ! is_array($data)){
        $data = array('name' => $data);
      }

      // Type and value are required attributes
      $data += array(
        'type'  => 'text',
        'value' => $value
      );

      return '<input '.App::getHelper('Html')->attributes($data).' '.$extra.' />';
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function hidden($data, $value = ''){
      if ( ! is_array($data)){
        $data = array(
          $data => $value
        );
      }

      $input = '';
      foreach ($data as $name => $value){
        $attr = array(
          'type'  => 'hidden',
          'name'  => $name,
          'value' => $value
        );

        $input .= FormHelper::input($attr)."\n";
      }

      return $input;
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function password($data, $value = '', $extra = ''){
      
      if(!is_array($data)){
        $data = array('name' => $data);
      }
      $data['type'] = 'password';

      return FormHelper::input($data, $value, $extra);
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function upload($data, $value = '', $extra = ''){
     
      if(!is_array($data)){
        $data = array('name' => $data);
      }
      $data['type'] = 'file';

      return FormHelper::input($data, $value, $extra);
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function textarea($data, $value = '', $extra = '', $double_encode = true){
      
      if (!is_array($data)){
        $data = array('name' => $data);
      }

      // Use the value from $data if possible, or use $value
      $value = isset($data['value']) ? $data['value'] : $value;

      // Value is not part of the attributes
      unset($data['value']);

      return '<textarea'.App::getHelper('Html')->attributes($data).' '.$extra.'>'.App::getHelper('Html')->specialchars($value, $double_encode).'</textarea>';
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function checkbox($data, $value = '', $checked = false, $extra = ''){
      if (!is_array($data)){
        $data = array('name' => $data);
      }

      $data['type'] = 'checkbox';

      if ($checked == TRUE OR (isset($data['checked']) AND $data['checked'] == TRUE)){
        $data['checked'] = 'checked';
      }else{
        unset($data['checked']);
      }

      return FormHelper::input($data, $value, $extra);
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function radio($data = '', $value = '', $checked = FALSE, $extra = ''){
      
      if (!is_array($data)){
        $data = array('name' => $data);
      }

      $data['type'] = 'radio';

      if ($checked == TRUE OR (isset($data['checked']) AND $data['checked'] == TRUE)){
        $data['checked'] = 'checked';
      }else{
        unset($data['checked']);
      }

      return FormHelper::input($data, $value, $extra);
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function submit($data = '', $value = '', $extra = ''){
      
      if(!is_array($data)){
        $data = array('name' => $data);
      }

      if (empty($data['name'])){
        // Remove the name if it is empty
        unset($data['name']);
      }

      $data['type'] = 'submit';

      return FormHelper::input($data, $value, $extra);
    }

  /**
  * ...
  *
  * @return ?
  */
    public static function button($data = '', $value = '', $extra = ''){
      
      if(!is_array($data)){
        $data = array('name' => $data);
      }

      if (empty($data['name'])){
        // Remove the name if it is empty
        unset($data['name']);
      }

      if (isset($data['value']) AND empty($value)){
        $value = $data['value'];
        unset($data['value']);
      }

      return '<button'.App::getHelper('Html')->attributes($data).' '.$extra.'>'.$value.'</button>';
    }
    
  /**
  * ...
  *
  * @return ?
  */
    public static function select($data, $options = null, $selected = null, $extra = ''){
      
        if ( ! is_array($data)){
          $data = array('name' => $data);
        }else{
          if (isset($data['options'])){
            // Use data options
            $options = $data['options'];
          }

          if (isset($data['selected'])){
            // Use data selected
            $selected = $data['selected'];
          }
        }

        if (is_array($selected)){
          // Multi-select box
          $data['multiple'] = 'multiple';
        } else {
          // Single selection (but converted to an array)
          $selected = array($selected);
        }

        $input = '<select'.App::getHelper('Html')->attributes($data).' '.$extra.'>'."\n";
        
        foreach ((array) $options as $key => $val) {
          // Key should always be a string
          $key = (string) $key;

          if (is_array($val)){
            $input .= '<optgroup label="'.$key.'">'."\n";
            foreach ($val as $inner_key => $inner_val){
              // Inner key should always be a string
              $inner_key = (string) $inner_key;

              $sel = in_array($inner_key, $selected) ? ' selected="selected"' : '';
              $input .= '<option value="'.$inner_key.'"'.$sel.'>'.$inner_val.'</option>'."\n";
            }
            $input .= '</optgroup>'."\n";
          } else {
            $sel = in_array($key, $selected) ? ' selected="selected"' : '';
            $input .= '<option value="'.$key.'"'.$sel.'>'.$val.'</option>'."\n";
          }
        }
        
        $input .= '</select>';

        return $input;
    }
   
  }

?>