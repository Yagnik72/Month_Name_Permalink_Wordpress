<?php

/**
* USE of monthname (january, june) and monthcode (jan, jun) in permalinks 
* %monthname% | %monthcode% 
*/
class Month_Name_Permalink {

  /**
   * Month Names
   */
  public static $monthnames = array(
      'january',
      'february',
      'march',
      'april',
      'may',
      'june',
      'july',
      'august',
      'september',
      'october',
      'november',
      'december',
  );
  
  /**
   * Month Codes
   */
  public static $monthcodes = array(
      'jan',
      'feb',
      'mar',
      'apr',
      'may',
      'jun',
      'jul',
      'aug',
      'sep',
      'oct',
      'nov',
      'dec',
  );
  
  /**
   * Registers all required hooks
   */
  public static function init() {
      add_rewrite_tag( '%monthname%', '(' . implode('|', self::$monthnames) . ')' );
      add_rewrite_tag( '%monthcode%', '(' . implode('|', self::$monthcodes) . ')' );
      add_rewrite_rule(
          '^([0-9]{4})/(' . implode( '|', self::$monthnames ) . ')/([0-9]{1,2})/(.*)?',
          'index.php?name=$matches[4]',
          'top'
      );
      add_rewrite_rule(
          '^([0-9]{4})/(' . implode( '|', self::$monthcodes ) . ')/([0-9]{1,2})/(.*)?',
          'index.php?name=$matches[4]',
          'top'
      );       
  
  }
  /**
   * Filters the month name and month code tags
   */
  public static function filter_post_link( $permalink, $post ) {
      if ( false === strpos( $permalink, '%monthname%' ) && false === strpos( $permalink, '%monthcode%' ) ) {
          return $permalink;
      }
  
      try {
          $monthindex = intval(get_post_time( 'n', "GMT" == false, $post->ID ));
  
          $monthname = self::$monthnames[$monthindex - 1];
          $monthcode = self::$monthcodes[$monthindex - 1];
  
          $permalink = str_replace( '%monthname%', $monthname, $permalink );
          $permalink = str_replace( '%monthcode%', $monthcode, $permalink );
  
          return $permalink;
      } catch (Exception $e) {
          return $permalink;
      }
  }
  
}
  
add_action( 'init', array( 'Month_Name_Permalink', 'init' ) );
add_filter( 'post_link', array( 'Month_Name_Permalink', 'filter_post_link' ), 10, 2 );
