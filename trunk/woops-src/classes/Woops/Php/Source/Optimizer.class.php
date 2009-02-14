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
 * PHP source code optimizer
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Php.Source
 */
class Woops_Php_Source_Optimizer
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The optimized PHP code
     */
    protected $_optimizedCode = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The PHP code to optimize
     * @return  NULL
     */
    public function __construct( $source )
    {
        // Gets the code tokens
        $tokens = token_get_all( ( string )$source );
        
        // Storage
        $codeLines = array();
        $lastToken = false;
        
        // Process each token
        foreach( $tokens as $key => $token ) {
            
            // Checks if the token is an array
            if( is_array( $token ) ) {
                
                // Do not process comments or whitespace
                if( $token[ 0 ] === T_COMMENT
                    || $token[ 0 ] === T_DOC_COMMENT
                ) {
                    continue;
                }
                
                // Do not keep a whitespace if the last token wasn't a PHP token
                if( $token[ 0 ] === T_WHITESPACE
                    && !is_array( $lastToken )
                ) {
                    continue;
                }
                
                // Do not keep carriage return after the PHP open tag
                if( $token[ 0 ] === T_OPEN_TAG
                    || $token[ 0 ] === T_OPEN_TAG_WITH_ECHO
                ) {
                    $token[ 1 ] = trim( $token[ 1 ] ) . ' ';
                }
                
                // Do not keep a whitespace after the PHP open tag
                if( $token[ 0 ] === T_WHITESPACE
                    && is_array( $lastToken )
                    && ( $lastToken[ 0 ] === T_OPEN_TAG
                    ||   $lastToken[ 0 ] === T_OPEN_TAG_WITH_ECHO )
                ) {
                    continue;
                }
                
                // Removes whitespace before the PHP close tag
                if( $token[ 0 ] === T_CLOSE_TAG
                    && is_array( $lastToken )
                    && $lastToken[ 0 ] === T_WHITESPACE
                ) {
                    array_pop( $codeLines );
                }
                
                // Do not keep a whitespace after the PHP assignation and comparison tokens
                if( $token[ 0 ] === T_WHITESPACE
                    && is_array( $lastToken )
                    && ( $lastToken[ 0 ] === T_AND_EQUAL
                    ||   $lastToken[ 0 ] === T_CONCAT_EQUAL
                    ||   $lastToken[ 0 ] === T_DIV_EQUAL
                    ||   $lastToken[ 0 ] === T_IS_EQUAL
                    ||   $lastToken[ 0 ] === T_IS_GREATER_OR_EQUAL
                    ||   $lastToken[ 0 ] === T_IS_NOT_EQUAL
                    ||   $lastToken[ 0 ] === T_IS_SMALLER_OR_EQUAL
                    ||   $lastToken[ 0 ] === T_MINUS_EQUAL
                    ||   $lastToken[ 0 ] === T_MOD_EQUAL
                    ||   $lastToken[ 0 ] === T_MUL_EQUAL
                    ||   $lastToken[ 0 ] === T_OR_EQUAL
                    ||   $lastToken[ 0 ] === T_PLUS_EQUAL
                    ||   $lastToken[ 0 ] === T_SL_EQUAL
                    ||   $lastToken[ 0 ] === T_SR_EQUAL
                    ||   $lastToken[ 0 ] === T_XOR_EQUAL
                    ||   $lastToken[ 0 ] === T_DOUBLE_ARROW
                    ||   $lastToken[ 0 ] === T_BOOLEAN_AND
                    ||   $lastToken[ 0 ] === T_BOOLEAN_OR
                    ||   $lastToken[ 0 ] === T_IS_IDENTICAL
                    ||   $lastToken[ 0 ] === T_IS_NOT_IDENTICAL )
                ) {
                    continue;
                }
                
                // Removes whitespace before the PHP assignation and comparison tokens
                if( is_array( $lastToken )
                    && $lastToken[ 0 ] === T_WHITESPACE
                    && ( $token[ 0 ] === T_AND_EQUAL
                    ||   $token[ 0 ] === T_CONCAT_EQUAL
                    ||   $token[ 0 ] === T_DIV_EQUAL
                    ||   $token[ 0 ] === T_IS_EQUAL
                    ||   $token[ 0 ] === T_IS_GREATER_OR_EQUAL
                    ||   $token[ 0 ] === T_IS_NOT_EQUAL
                    ||   $token[ 0 ] === T_IS_SMALLER_OR_EQUAL
                    ||   $token[ 0 ] === T_MINUS_EQUAL
                    ||   $token[ 0 ] === T_MOD_EQUAL
                    ||   $token[ 0 ] === T_MUL_EQUAL
                    ||   $token[ 0 ] === T_OR_EQUAL
                    ||   $token[ 0 ] === T_PLUS_EQUAL
                    ||   $token[ 0 ] === T_SL_EQUAL
                    ||   $token[ 0 ] === T_SR_EQUAL
                    ||   $token[ 0 ] === T_XOR_EQUAL
                    ||   $token[ 0 ] === T_DOUBLE_ARROW
                    ||   $token[ 0 ] === T_BOOLEAN_AND
                    ||   $token[ 0 ] === T_BOOLEAN_OR
                    ||   $token[ 0 ] === T_IS_IDENTICAL
                    ||   $token[ 0 ] === T_IS_NOT_IDENTICAL )
                ) {
                    array_pop( $codeLines );
                }
                
                // Stores the code for the current token
                $codeLines[] = $token[ 1 ];
                
            } else {
                
                // Removes whitespace before some characters
                if( is_array( $lastToken )
                    && $lastToken[ 0 ] === T_WHITESPACE
                    && ( $token === '.'
                    ||   $token === '=' 
                    ||   $token === '{' 
                    ||   $token === '}' 
                    ||   $token === '(' 
                    ||   $token === ')' 
                    ||   $token === '[' 
                    ||   $token === ']' 
                    ||   $token === '|' 
                    ||   $token === '&' 
                    ||   $token === '~' 
                    ||   $token === '^' 
                    ||   $token === '?' 
                    ||   $token === ':' )
                ) {
                    array_pop( $codeLines );
                }
                
                // Stores the code
                $codeLines[] = $token;
            }
            
            // Stores the last token
            $lastToken = $token;
        }
        
        // Stores the optimized code
        $this->_optimizedCode = implode( '', $codeLines );
    }
    
    /**
     * Gets the optimized version of the PHP source code
     * 
     * @return  string  The optimized version of the PHP source code
     */
    public function __toString()
    {
        return $this->_optimizedCode;
    }
}
