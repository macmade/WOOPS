<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Array utilities
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Array
 */
final class Woops_Array_Utils extends Woops_Core_Object implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Array_Utils   The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Gets an HTML list from a array
     * 
     * @param   string              The array to process
     * @param   string              The list tag (ul or ol)
     * @return  Woops_Xhtml_Tag     The HTML list
     */
    public function toList( array $array, $listType = 'ul' )
    {
        // Creates the list tag
        $list = new Woops_Xhtml_Tag( $listType );
        
        // Process each list item
        foreach( $array as $item ) {
            
            // Checks if the current item is an array
            if( is_array( $item ) ) {
                
                // Creates a sub-list
                $list->li->addChild( $this->arrayToList( $item, $listType ) );
                
            } else {
                
                // Adds the list item to the list tag
                $list->li = trim( $item );
            }
        }
        
        // Returns the list tag
        return $list;
    }
    
    /**
     * Gets a flattened array from a multidimensionnal array
     * 
     * This method will converts a multidimensionnal array as a one-dimension
     * array. The key of each item will represent the structure of the
     * original multidimensionnal array. For instance:
     * 
     * <code>
     * array(
     *      'foo' => array(
     *          'bar'   => true,
     *          'fooBar => false
     *      )
     * )
     * </code>
     * 
     * will be converted to:
     * 
     * <code>
     * array(
     *      'foo[bar]'    => true,
     *      'foo[fooBar]' => false
     * )
     * </code>
     * 
     * @param   array   The array to flatten
     * @param   string  The key prefix (used internally)
     * @return  array   An array with all the values, flattened
     */
    public function flatten( array $array, $prefix = '' )
    {
        // Storage for the items
        $items = array();
        
        // Process each item
        foreach( $array as $key => $value ) {
            
            // Key for the current item
            $curKey = ( $prefix ) ? $prefix . '[' . $key . ']' : $key;
            
            // Checks if we have a sub-array
            if( is_array( $value ) ) {
                
                // Gets the sub-values
                $subValues = $this->flatten( $value, $curKey );
                
                // Process each sub-values
                foreach( $subValues as $subKey => $subValue ) {
                    
                    // Adds the current sub-value
                    $items[ $subKey ] = $subValue;
                }
                
            } else {
                
                // Adds the current value
                $items[ $curKey ] = $value;
            }
        }
        
        // Returns the flattened array
        return $items;
    }
}
