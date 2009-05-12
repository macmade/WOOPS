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
namespace Woops\Helper;

/**
 * String utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.String
 */
class StringUtilities extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The ASCII table (control characters only)
     */
    protected $_asciiTable      = array();
    
    /**
     * Whether the random number generator device exists
     */
    protected $_hasRandomDevice = false;
    
    /**
     * A file resource to the random generator device, if available
     */
    protected $_randomDevice    = NULL;
    
    /**
     * The name of the ASCII control characters
     */
    protected $_asciiName       = array(
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
     * @return void
     */
    protected function __construct()
    {
        // Process each ASCII control character
        for( $i = 0; $i < 33; $i++ ) {
            
            // Stores the character
            $this->_asciiTable[ $this->_asciiName[ $i ] ] = chr( $i );
        }
        
        // Sets the newline character
        $this->_asciiTable[ 'NL' ] = $this->_asciiTable[ 'LF' ];
        
        // Checks if a random generator device is available
        if( file_exists( '/dev/urandom' ) && is_readable( '/dev/urandom' ) ) {
            
            // Creates a file handle to the random generator device
            $this->_hasRandomDevice = true;
            $this->_randomDevice    = fopen( '/dev/urandom', 'rb' );
            
        } elseif( file_exists( '/dev/random' ) && is_readable( '/dev/random' ) ) {
            
            // Creates a file handle to the random generator device
            $this->_hasRandomDevice = true;
            $this->_randomDevice    = fopen( '/dev/random', 'rb' );
        }
    }
    
    /**
     * Class destructor
     * 
     * @return  void
     */
    public function __destruct()
    {
        // Checks if the random device was available on construct time
        if( $this->_hasRandomDevice ) {
            
            // Closes the file handle
            fclose( $this->_randomDevice );
        }
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
        return ( isset( $this->_asciiTable[ $name ] ) ) ? $this->_asciiTable[ $name ] : '';
    }
    
    /**
     * Gets an ASCII control character multiple times
     * 
     * Valid name are: NUL, SOH, STX, ETX, EOT, ENQ, ACK, BEL, BS, TAB, LF, VT,
     * FF, CR, SO, SI, DLE, DC1, DC2, DC3, DC4, NAK, SYN, ETB, CAN, EM, SUB,
     * ESC, FS, GS, RS, US, SPC and NL.
     * 
     * @param   string  The name of the ASCII control character
     * @param   array   The arguments of the method. Only the first one will be used, as number of occurences of the character to return.
     * @return  string  The ASCII control character
     */
    public function __call( $name, array $args )
    {
        // Number of characters to produce
        $repeat = ( isset( $args[ 0 ] ) ) ? ( int )$args[ 0 ] : 1;
        
        // Returns the ASCII character
        return ( isset( $this->_asciiTable[ $name ] ) ) ? str_repeat( $this->_asciiTable[ $name ], $repeat ) : '';
    }
    
    /**
     * Sets the character to use as the new line character
     * 
     * @param   string  The new line character to set
     * @return  string  The previsou value
     */
    public function setNewLine( $char )
    {
        // Gets the current value
        $prev                     = $this->_asciiTable[ 'NL' ];
        
        // Stores the new value
        $this->_asciiTable[ 'NL' ] = ( string )$char;
        
        // Returns the previous value
        return $prev;
    }
    
    /**
     * Generates an unique identifier
     * 
     * This method generates a unique identifier as defined in RFC-4122
     * (version 4).
     * Many thanks to the contributors of the documentation for the PHP function
     * uniqid().
     * 
     * @return  string  An unique identifier
     */
    public function uniqueId()
    {
        // Checks if the random generator device is available
        if( $this->_hasRandomDevice ) {
            
            // Gets 16 random bytes
            $randomBits = fread( $this->_randomDevice, 16 );
            
        } else {
            
            // No, we'll have to generates the bytes by ourselves
            $randomBits = '';
            
            // We want 16 bytes
            for( $i = 0; $i < 16; $i++ ) {
                
                // Generates a random byte
                $randomBits = chr( mt_rand( 0, 0xFF ) );
            }
        }
        
        // Creates the needed parts, according to RFC-4122
        $timeLow                 = bin2hex( substr( $randomBits, 0, 4 ) );
        $timeMid                 = bin2hex( substr( $randomBits, 4, 2 ) );
        $timeHighAndVersion      = ( hexdec( bin2hex( substr( $randomBits, 6, 2 ) ) ) >> 4 ) | 0x4000;
        $clockSeqHighAndReserved = ( hexdec( bin2hex( substr( $randomBits, 8, 2 ) ) ) >> 2 ) | 0x8000;
        $node                    = bin2hex( substr( $randomBits, 10, 6 ) );
        
        // Puts all the parts together
        $uid = sprintf(
            '%08s-%04s-%04x-%04x-%012s',
            $timeLow,
            $timeMid,
            $timeHighAndVersion,
            $clockSeqHighAndReserved,
            $node
        );
        
        // Returns the unique identifier
        return $uid;
    }
    
    /**
     * Gets an unique identifier URN resource
     * 
     * @return  string  The UUID URN
     * @see     uniqueId
     */
    public function uniqueIdUrn()
    {
        return 'urn:uuid:' . $this->uniqueId();
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
     * @return  Woops\Xhtml\Tag The HTML list
     */
    public function toList( $str, $sep = ',', $listType = 'ul' )
    {
        // Gets all the list items
        $items = explode( $sep, $str );
        
        // Creates the list tag
        $list = new \Woops\Xhtml\Tag( $listType );
        
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
    function crop( $str, $chars, $endString = '...', $cropToSpace = true, $stripTags = true )
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
            if( $cropToSpace && strstr( $str, ' ' ) ) {
                
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
