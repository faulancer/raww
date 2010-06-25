<?php

class Inflector{
  
  private static $uncountable = array(
		'access' => 1,
		'advice' => 1,
		'art' => 1,
		'baggage' => 1,
		'dances' => 1,
		'equipment' => 1,
		'fish' => 1,
		'fuel' => 1,
		'furniture' => 1,
		'food' => 1,
		'heat' => 1,
		'honey' => 1,
		'homework' => 1,
		'impatience' => 1,
		'information' => 1,
		'knowledge' => 1,
		'luggage' => 1,
		'money' => 1,
		'music' => 1,
		'news' => 1,
		'patience' => 1,
		'progress' => 1,
		'pollution' => 1,
		'research' => 1,
		'rice' => 1,
		'sand' => 1,
		'series' => 1,
		'sheep' => 1,
		'sms' => 1,
		'species' => 1,
		'staff' => 1,
		'toothpaste' => 1,
		'traffic' => 1,
		'understanding' => 1,
		'water' => 1,
		'weather' => 1,
		'work' => 1
	);

	private static $irregular = array(
		'child' => 'children',
		'clothes' => 'clothing',
		'man' => 'men',
		'movie' => 'movies',
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
		
    if(isset(self::$uncountable[strtolower($word)])){
      return $word;
    }
    
    
    if(isset(self::$irregular[strtolower($word)])){
      return self::$irregular[strtolower($word)];
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