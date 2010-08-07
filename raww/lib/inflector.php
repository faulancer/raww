<?php

class Inflector{
  
  private static $uncountable = array(
		'access',
		'advice',
		'art',
		'baggage',
		'equipment',
		'fish',
		'fuel',
		'furniture',
		'food',
		'heat',
		'honey',
		'homework',
		'impatience',
		'information',
		'knowledge',
		'luggage',
		'media',
		'money',
		'music',
		'news',
		'patience',
		'progress',
		'pollution',
		'research',
		'rice',
		'sand',
		'series',
		'sheep',
		'sms',
		'species',
		'staff',
		'toothpaste',
		'traffic',
		'understanding',
		'water',
		'weather',
		'work'
	);

	private static $irregular = array(
		'child' => 'children',
		'clothes' => 'clothing',
		'man' => 'men',
		'person' => 'people',
		'woman' => 'women',
		'mouse' => 'mice',
		'goose' => 'geese',
		'ox' => 'oxen',
		'leaf' => 'leaves',
		'course' => 'courses',
		'size' => 'sizes',
	);
  
  /**
  * ...
  *
  */ 
  public static function toPlural($word) {
		
    if(in_array($word, self::$uncountable[strtolower($word)])){
      return $word;
    }
    
    
    if(isset(self::$irregular[strtolower($word)])){
      return self::$irregular[strtolower($word)];
    }
    
    if(substr($word,-1)=='y'){
      $word = substr($word, 0, strlen($word)-1).'ie';
    }
    
    return $word .= 's';
	}
  
  /**
  * ...
  *
  */ 
	public static function toSingular($word) {
		return substr($word, 0, -1);
	}
  
  /**
  * ...
  *
  */ 
	public static function isPlural($word) {
		return substr($word, -1, 1) === 's';
	}
  
  /**
  * ...
  *
  */ 
  public static function underscore($word){
  
        return  strtolower(
                  preg_replace('/[^A-Z^a-z^0-9]+/','_',
                  preg_replace('/([a-z\d])([A-Z])/','\1_\2',
                  preg_replace('/([A-Z]+)([A-Z][a-z])/','\1_\2',$word)))
                );
  }
  
  /**
  * ...
  *
  */ 
  public static function camelize($word){
        return str_replace(' ','',ucwords(preg_replace('/[^A-Z^a-z^0-9]+/',' ',$word)));
  }
}

?>