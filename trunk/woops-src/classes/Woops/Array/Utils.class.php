<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Array utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Array
 */
class Woops_Array_Utils implements Woops_Core_Static_Class_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Class constructor
     * 
     * The class constructor is private as all methods from this class are
     * static.
     * 
     * @return  NULL
     */
    private function __construct()
    {}
    
    /**
     * Gets an HTML list from a array
     * 
     * @param   string              The array to process
     * @param   string              The list tag (ul or ol)
     * @return  Woops_Xhtml_Tag    The HTML list
     */
    public static function arrayToList( array $array, $listType = 'ul' )
    {
        // Creates the list tag
        $list = new Woops_Xhtml_Tag( $listType );
        
        // Process each list item
        foreach( $array as $item ) {
            
            // Checks if the current item is an array
            if( is_array( $item ) ) {
                
                // Creates a sub-list
                $list->li->addChild( self::arrayToList( $item, $listType ) );
                
            } else {
                
                // Adds the list item to the list tag
                $list->li = trim( $item );
            }
        }
        
        // Returns the list tag
        return $list;
    }
}
