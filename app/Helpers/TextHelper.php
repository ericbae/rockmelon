<?php

namespace App\Helpers;
// require(base_path() . '/external-libraries/HTMLTextLimiter/DOMLettersIterator.php'); 
require_once(base_path() . '/external-libraries/HTMLTextLimiter/DOMWordsIterator.php'); 

use Exception, phpQuery, Image, Requests, DB, Log, DOMDocument, MailChecker;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DOMWordsIterator;
use DOMText;
use DOMNode;

class TextHelper {

  public static function truncateWords($html, $limit, $ellipsis = '...') {  
    if($limit <= 0 || $limit >= self::countWords(strip_tags($html)))
      return $html;
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    $body = $dom->getElementsByTagName("body")->item(0);
    
    $it = new DOMWordsIterator($body);
    
    foreach($it as $word) {            
      if($it->key() >= $limit) {
        $currentWordPosition = $it->currentWordPosition();
        $curNode             = $currentWordPosition[0];
        $offset              = $currentWordPosition[1];
        $words               = $currentWordPosition[2];
        $curNode->nodeValue  = substr($curNode->nodeValue, 0, $words[$offset][1] + strlen($words[$offset][0]));
        
        self::removeProceedingNodes($curNode, $body);
        self::insertEllipsis($curNode, $ellipsis);
        break;
      }
    }
    
    return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML());
  }

  private static function removeProceedingNodes(DOMNode $domNode, DOMNode $topNode) {        
      $nextNode = $domNode->nextSibling;
      
      if($nextNode !== NULL) {
        self::removeProceedingNodes($nextNode, $topNode);
        $domNode->parentNode->removeChild($nextNode);
      } 

      else {
        //scan upwards till we find a sibling
        $curNode = $domNode->parentNode;
        while($curNode !== $topNode) {
          if($curNode->nextSibling !== NULL) {
            $curNode = $curNode->nextSibling;
            self::removeProceedingNodes($curNode, $topNode);
            $curNode->parentNode->removeChild($curNode);
            break;
          }

          $curNode = $curNode->parentNode;
        }
      }
    }
    
    private static function insertEllipsis(DOMNode $domNode, $ellipsis) {    
      $avoid = array('a', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5'); //html tags to avoid appending the ellipsis to
      
      if( in_array($domNode->parentNode->nodeName, $avoid) && $domNode->parentNode->parentNode !== NULL) {
        // Append as text node to parent instead
        $textNode = new DOMText($ellipsis);
        
        if($domNode->parentNode->parentNode->nextSibling)
          $domNode->parentNode->parentNode->insertBefore($textNode, $domNode->parentNode->parentNode->nextSibling);
        else
          $domNode->parentNode->parentNode->appendChild($textNode);
      } 

      else {
        // Append to current node
        $domNode->nodeValue = rtrim($domNode->nodeValue).$ellipsis;
      }
    }

  private static function countWords($text) {
    $words = preg_split("/[\n\r\t ]+/", $text, -1, PREG_SPLIT_NO_EMPTY);
    return count($words);
  }
}