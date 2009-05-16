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
 * Brainfuck interpreter
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Binary
 */
class Interpreter extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The brainfuck stack
     */
    protected $_stack          = array();
    
    /**
     * The result of the brainfuck code
     */
    protected $_result         = '';
    
    /**
     * The current stack index
     */
    protected $_index          = 0;
    
    /**
     * Class constructor
     * 
     * @param   string  The brainfuck code to interpret
     * @return  void
     */
    public function __construct( $code )
    {
        // Initializes the stack
        $this->_stack[ 0 ] = 0;
        
        // Starts to interpret the code
        $this->_interpret( ( string )$code );
    }
    
    /**
     * Gets the result of the brainfuck code
     * 
     * @return  string  The result of the brainfuck code
     */
    public function __toString()
    {
        return $this->_result;
    }
    
    /**
     * Interprets brainfuck code
     * 
     * @param   string                  The brainfuck code to interpret
     * @return  void
     * @throws  Interpreter\Exception   If an non-terminated loop is found
     */
    protected function _interpret( $code )
    {
        // Gets the code length
        $length            = strlen( $code );
        
        // Process each character
        for( $i = 0; $i < $length; $i++ ) {
            
            // Gets the current character
            $char = $code[ $i ];
            
            // Checks the character
            switch( $char ) {
                
                // Incrementation
                case '+':
                    
                    // Increases the current value
                    $this->_stack[ $this->_index ]++;
                    break;
                
                // Decrementation
                case '-':
                    
                    // Decreases the current value
                    $this->_stack[ $this->_index ]--;
                    break;
                
                // Pointer incrementation
                case '>':
                    
                    // Increments the current pointer
                    $this->_index++;
                    
                    // Checks if the stack cell exists
                    if( !isset( $this->_stack[ $this->_index ] ) ) {
                        
                        // Creates the stack cell
                        $this->_stack[ $this->_index ] = 0;
                    }
                    break;
                
                // Pointer decrementation
                case '<':
                    
                    // Decreases the current pointer
                    $this->_index--;
                    
                    // Checks if the stack cell exists
                    if( !isset( $this->_stack[ $this->_index ] ) ) {
                        
                        // Creates the stack cell
                        $this->_stack[ $this->_index ] = 0;
                    }
                    break;
                
                case '[':
                    
                    // Position of the end loop character
                    $endLoop = ( strpos( $code, ']', $i ) + 1);
                    
                    // Checks if we have an end loop character
                    if( $endLoop === false ) {
                        
                        // Error - No end of loop
                        throw new Interpreter\Exception(
                            'Brainfuck loop without an end',
                            Interpreter\Exception::EXCEPTION_NO_END_OF_LOOP
                        );
                    }
                    
                    // Gets the code to loop
                    $loopCode = substr( $code, $i + 1, $endLoop - ( $i + 1 ) );
                    
                    // Value of the current cell
                    $index    = $this->_index;
                    
                    // Loops until 0
                    while( $this->_stack[ $index ] !== 0 ) {
                        
                        // Interprets the loop code
                        $this->_interpret( $loopCode );
                    }
                    
                    // Moves to the end of the loop code
                    $i = $endLoop - 1;
                    break;
                
                case '.':
                    
                    // Adds the character of the current stack cell to the result string
                    $this->_result .= chr( $this->_stack[ $this->_index ] );
                    break;
                
                default:
                    
                    // Unrecognized character
                    break;
            }
        }
    }
}
