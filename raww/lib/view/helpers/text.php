<?php
  
  class TextHelper{
    
  /**
  * ...
  *
  * @return ?
  */
    public static function autoUrls($text){
      // Finds all http/https/ftp/ftps links that are not part of an existing html anchor
      if (preg_match_all('~\b(?<!href="|">)(?:ht|f)tps?://\S+(?:/|\b)~i', $text, $matches)){
        foreach ($matches[0] as $match){
          // Replace each link with an anchor
          $text = str_replace($match, html::anchor($match), $text);
        }
      }
      // Find all naked www.links.com (without http://)
      if (preg_match_all('~\b(?<!://)www(?:\.[a-z0-9][-a-z0-9]*+)+\.[a-z]{2,6}\b~i', $text, $matches)){
        foreach ($matches[0] as $match){
          // Replace each link with an anchor
          $text = str_replace($match, html::anchor('http://'.$match, $match), $text);
        }
      }
      return $text;
    }
    
  }