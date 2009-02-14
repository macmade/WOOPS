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
 * String utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.String
 */
final class Woops_String_Utils implements Woops_Core_Singleton_Interface
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
     * The ASCII table (control characters only)
     */
    private $_asciiTable      = array();
    
    /**
     * The name of the ASCII control characters
     */
    private $_asciiName       = array(
        'NUL', 'SOH', 'STX', 'ETX', 'EOT', 'ENQ', 'ACK', 'BEL', 'BS',  'TAB',
        'LF',  'VT',  'FF',  'CR',  'SO',  'SI',  'DLE', 'DC1', 'DC2', 'DC3',
        'DC4', 'NAK', 'SYN', 'ETB', 'CAN', 'EM',  'SUB', 'ESC', 'FS',  'GS',
        'RS',  'US',  'SPC'
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return NULL
     */
    private function __construct()
    {
        // Process each ASCII control character
        for( $i = 0; $i < 33; $i++ ) {
            
            // Stores the character
            $this->_asciiTable[ $this->_asciiName[ $i ] ] = chr( $i );
        }
        
        // Sets the newline character
        $this->_asciiTable[ 'NL' ] = $this->_asciiTable[ 'LF' ];
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
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
     * Gets an ASCII control character
     * 
     * Valid name are: NUL, SOH, STX, ETX, EOT, ENQ, ACK, BEL, BS, TAB, LF, VT,
     * FF, CR, SO, SI, DLE, DC1, DC2, DC3, DC4, NAK, SYN, ETB, CAN, EM, SUB,
     * ESC, FS, GS, RS, US, SPC and NL.
     * 
     * @param   string  The name of the ASCII control character
     * @return  string  The ASCII control character
     */
    public function __get( $name )
    {
        return ( $this->_asciiTable[ $name ] ) ? $this->_asciiTable[ $name ] : '';
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_String_Utils  The unique instance of the class
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
     * Unify line breaks
     * 
     * This method converts Macintosh & DOS line breaks to standard Unix
     * line breaks. This means replacing CR (u000D / chr(13)) and CR + LF
     * (u000D + u000A / chr(13) + chr( 10 )) by LF (u000A / chr( 10 )). It also
     * replace LF + CR (u000A + u000D / chr( 10 ) + chr(13)) sequences. By
     * default, the function erases all ASCII null characters (u0000 / chr(0)).
     * 
     * @param   string  The text to process
     * @param   boolean If set, erases ASCII null characters
     * @return  string  The text with standard Unix line breaks
     */
    public function unifyLineBreaks( $text, $stripNull = true )
    {
        // Strip ASCII null character?
        if( $stripNull ) {
            
            // Erases ASCII null characters
            $text = str_replace( $this->_asciiTable[ 'NUL' ], '', $text );
        }
        
        // DOS CR + LF (u000D + u000A / chr(13) + chr( 10 ))
        $text = str_replace(
            $this->_asciiTable[ 'CR' ] . $this->_asciiTable[ 'LF' ],
            $this->_asciiTable[ 'LF' ],
            $text
        );
        
        // LF + CR (u000A + u000D / chr( 10 ) + chr(13))
        $text = str_replace(    
            $this->_asciiTable[ 'LF' ] . $this->_asciiTable[ 'CR' ],
            $this->_asciiTable[ 'LF' ],
            $text
        );
        
        // Macintosh CR (u000D / chr(13))
        $text = str_replace(
            $this->_asciiTable[ 'CR' ],
            $this->_asciiTable[ 'LF' ],
            $text
        );
        
        // Return text
        return $text;
    }
    
    /**
     * Gets an HTML list from a string
     * 
     * @param   string          The string to process
     * @param   string          The separator for the list items
     * @param   string          The list tag (ul or ol)
     * @return  Woops_Xhtml_Tag The HTML list
     */
    public function strToList( $str, $sep = ',', $listType = 'ul' )
    {
        // Gets all the list items
        $items = explode( $sep, $str );
        
        // Creates the list tag
        $list = new Woops_Xhtml_Tag( $listType );
        
        // Process each list item
        foreach( $items as $item ) {
            
            // Adds the list item to the list tag
            $list->li = trim( $item );
        }
        
        // Returns the list tag
        return $list;
    }
    
    /**
     * Crops a string.
     * 
     * This function is used to crop a string to a specified number of
     * characters. By default, it crops the string after an entire word, and not
     * in the middle of a word. It also strips by default all HTML tags before
     * cropping, to avoid display problems.
     * 
     * @param   string  The string to crop
     * @param   int     The number of characters to keep
     * @param   string  The string to add after the cropped string
     * @param   boolean If set, don't crop in a middle of a word
     * @param   boolean If set, removes all HTML tags from the string before cropping
     * @return  string  The cropped string
     */
    function crop( $str, $chars, $endString = '...', $crop2space = true, $stripTags = true )
    {
        // Checks the string length
        if( strlen( $str ) < $chars ) {
            
            // Returns the string
            return $str;
        }
        
        // Remove HTML tags?
        if( $stripTags ) {
            
            // Removes all tags
            $str = strip_tags( $str );
        }
        
        // Checks the string length
        if( strlen( $str ) < $chars ) {
            
            // Returns the string
            return $str;
            
        } else {
            
            // Substring
            $str = substr( $str, 0, $chars );
            
            // Crops only after a word?
            if( $crop2space && strstr( $str, ' ' ) ) {
                
                // Position of the last space
                $cropPos = strrpos( $str, ' ' );
                
                // Crops the string
                $str     = substr( $str, 0, $cropPos );
            }
            
            // Returns the string
            return $str . $endString;
        }
    }
}
