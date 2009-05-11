<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id: Utils.class.php 824 2009-05-10 03:43:04Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Helpers;

/**
 * Array utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Array
 */
class ArrayUtilities extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Gets an HTML list from a array
     * 
     * @param   string              The array to process
     * @param   string              The list tag (ul or ol)
     * @return  Woops\Xhtml\Tag     The HTML list
     */
    public function toList( array $array, $listType = 'ul' )
    {
        // Creates the list tag
        $list = new \Woops\Xhtml\Tag( $listType );
        
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
