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

# $Id: Stream.class.php 893 2009-05-12 16:34:26Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\BrainFuck;

/**
 * Brainfuck writer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Binary
 */
class Writer extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The resulting brainfuck code
     */
    protected $_code  = '';
    
    /**
     * The brainfuck stack
     */
    protected $_stack = array();
    
    /**
     * The ASCII letters, with pointers to their values in the stack array
     */
    protected $_chars = array();
    
    /**
     * The stack pointer
     */
    protected $_index = 0;
    
    /**
     * Class constructor
     * 
     * @param   string  The string to converts to brainfuck
     * @return  void
     */
    public function __construct( $str )
    {
        // Ensures we have a string
        $str = ( string )$str;
        
        // Checks for a value
        if( $str )
        {
            // Creates the brainfuck code
            $this->_createCode( $str );
        }
    }
    
    /**
     * Creates the brainfuck code
     * 
     * @param   string  The string to converts to brainfuck
     * @return  void
     */
    protected function _createCode( $str )
    {
        // Gets each letter of the input string
        $letters = preg_split( '//', $str, 0, PREG_SPLIT_NO_EMPTY );
        
        // Removes duplicate letters and sorts the letters by ASCII value
        $letters = array_flip( $letters );
        ksort( $letters );
        $letters = array_flip( $letters );
        
        // Length of the input string
        $strLen     = strlen( $str );
        
        // Starts a loop (10x) to create the brainfuck stack
        $this->_code = '++++++++++[';
        
        // Process each unique letter
        foreach( $letters as $letter )
        {
            // Rounded ASCII value
            $ascii = floor( ord( $letter ) / 10 );
            
            // Checks if we already have a stack value for this letter
            if( !isset( $this->_chars[ $ascii ] ) )
            {
                // Increments the stack pointer
                $this->_index++;
                
                // Adds the stack index for the current value
                $this->_chars[ $ascii ] = $this->_index;
                
                // Adds the ASCII value to the brainfuck stack
                $this->_stack[ $this->_index ] = $ascii * 10;
                
                // Creates the corresponding code
                $this->_code .= '>' . str_repeat( '+', $ascii );
            }
        }
        
        // Ends the loop
        $this->_code .= str_repeat( '<', $this->_index ) . '-]';
        
        // Resets the stack pointer
        $this->_index = 0;
        
        // Process each letter of the input string
        for( $i = 0; $i < $strLen; $i++ )
        {
            // Gets the ASCII value
            $charCode = ord( $str[ $i ] );
            $ascii    = floor( $charCode / 10 );
            
            // Checks if we need to move the stack pointer
            if( $this->_chars[ $ascii ] > $this->_index )
            {
                // Increases the stack pointer
                $this->_code .= str_repeat( '>', $this->_chars[ $ascii ] - $this->_index );
            }
            elseif( $this->_chars[ $ascii ] < $this->_index )
            {
                // Decreases the stack pointer
                $this->_code .= str_repeat( '<', $this->_index - $this->_chars[ $ascii ] );
            }
            
            // Sets the stack pointer to the correct value
            $this->_index = $this->_chars[ $ascii ];
            
            // Checks if the current stack value must be changed
            if( $charCode < $this->_stack[ $this->_index ] )
            {
                // Decreases the stack value
                $this->_code .= str_repeat( '-', $this->_stack[ $this->_index ] - $charCode );
            }
            elseif( $charCode > $this->_stack[ $this->_index ] )
            {
                // Increments the stack value
                $this->_code .= str_repeat( '+', $charCode - $this->_stack[ $this->_index ] );
            }
            
            // Sets the correct stack value
            $this->_stack[ $this->_index ] = $charCode;
            
            // Write instruction
            $this->_code .= '.';
        }
    }
    
    /**
     * Returns the brainfuck code
     * 
     * @return  string  The brainfuck code
     */
    public function __toString()
    {
        return $this->_code;
    }
}
