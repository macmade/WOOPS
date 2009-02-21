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
    protected $_optimizedCode       = '';
    
    /**
     * The names of the PHP superglobal variables
     */
    protected static $_superGlobals = array(
        '$_COOKIE'  => true,
        '$_ENV'     => true,
        '$_FILES'   => true,
        '$_GET'     => true,
        '$_POST'    => true,
        '$_REQUEST' => true,
        '$_SERVER'  => true,
        '$_SESSION' => true,
        '$GLOBALS'  => true
    );
    
    /**
     * The allowed characters for the generation of variable names
     */
    protected static $_varNameChars = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    );
    
    /**
     * Class constructor
     * 
     * @param   string  The PHP code to optimize
     * @param   boolean Wheter to rename local variables with the shortest possible name
     * @return  void
     * @see     _generateVarName
     */
    public function __construct( $source, $renameVariables = true )
    {
        // Gets the code tokens
        $tokens      = token_get_all( ( string )$source );
        
        # ----------------------------------------------------------------------
        # List of the available PHP tokens:
        # 
        # - T_ABSTRACT
        # - T_AND_EQUAL
        # - T_ARRAY
        # - T_ARRAY_CAST
        # - T_AS
        # - T_BAD_CHARACTER
        # - T_BOOL_CAST
        # - T_BOOLEAN_AND
        # - T_BOOLEAN_OR
        # - T_BREAK
        # - T_CASE
        # - T_CATCH
        # - T_CHARACTER
        # - T_CLASS
        # - T_CLASS_C
        # - T_CLONE
        # - T_CLOSE_TAG
        # - T_COMMENT
        # - T_CONCAT_EQUAL
        # - T_CONST
        # - T_CONSTANT_ENCAPSED_STRING
        # - T_CONTINUE
        # - T_CURLY_OPEN
        # - T_DEC
        # - T_DECLARE
        # - T_DEFAULT
        # - T_DIV_EQUAL
        # - T_DNUMBER
        # - T_DO
        # - T_DOC_COMMENT
        # - T_DOLLAR_OPEN_CURLY_BRACES
        # - T_DOUBLE_ARROW
        # - T_DOUBLE_CAST
        # - T_DOUBLE_COLON
        # - T_ECHO
        # - T_ELSE
        # - T_ELSEIF
        # - T_EMPTY
        # - T_ENCAPSED_AND_WHITESPACE
        # - T_END_HEREDOC
        # - T_ENDDECLARE
        # - T_ENDFOR
        # - T_ENDFOREACH
        # - T_ENDIF
        # - T_ENDSWITCH
        # - T_ENDWHILE
        # - T_EVAL
        # - T_EXIT
        # - T_EXTENDS
        # - T_FILE
        # - T_FINAL
        # - T_FOR
        # - T_FOREACH
        # - T_FUNC_C
        # - T_FUNCTION
        # - T_GLOBAL
        # - T_HALT_COMPILER
        # - T_IF
        # - T_IMPLEMENTS
        # - T_INC
        # - T_INCLUDE
        # - T_INCLUDE_ONCE
        # - T_INLINE_HTML
        # - T_INSTANCEOF
        # - T_INT_CAST
        # - T_INTERFACE
        # - T_IS_EQUAL
        # - T_IS_GREATER_OR_EQUAL
        # - T_IS_IDENTICAL
        # - T_IS_NOT_EQUAL
        # - T_IS_NOT_IDENTICAL
        # - T_IS_SMALLER_OR_EQUAL
        # - T_ISSET
        # - T_LINE
        # - T_LIST
        # - T_LNUMBER
        # - T_LOGICAL_AND
        # - T_LOGICAL_OR
        # - T_LOGICAL_XOR
        # - T_MINUS_EQUAL
        # - T_ML_COMMENT (deprecated)
        # - T_MOD_EQUAL
        # - T_MUL_EQUAL
        # - T_NEW
        # - T_NUM_STRING
        # - T_OBJECT_CAST
        # - T_OBJECT_OPERATOR
        # - T_OLD_FUNCTION (deprecated)
        # - T_OPEN_TAG
        # - T_OPEN_TAG_WITH_ECHO
        # - T_OR_EQUAL
        # - T_PAAMAYIM_NEKUDOTAYIM
        # - T_PLUS_EQUAL
        # - T_PRINT
        # - T_PRIVATE
        # - T_PROTECTED
        # - T_PUBLIC
        # - T_REQUIRE
        # - T_REQUIRE_ONCE
        # - T_RETURN
        # - T_SL
        # - T_SL_EQUAL
        # - T_SR
        # - T_SR_EQUAL
        # - T_START_HEREDOC
        # - T_STATIC
        # - T_STRING
        # - T_STRING_CAST
        # - T_STRING_VARNAME
        # - T_SWITCH
        # - T_THROW
        # - T_TRY
        # - T_UNSET
        # - T_UNSET_CAST
        # - T_USE
        # - T_VAR
        # - T_VARIABLE
        # - T_WHILE
        # - T_WHITESPACE
        # - T_XOR_EQUAL
        # ----------------------------------------------------------------------
        
        // Storage for the code lines to keep
        $codeLines   = array();
        
        // Global variables inside a function
        $funcGlobals = array();
        
        // Local variables inside a function
        $funcVars    = array();
        
        // Last processed token
        $lastToken   = false;
        
        // Flag to know if we are inside a function
        $inFunc      = false;
        
        // Flag to know if we are in a global variables declaration
        $inGlobal    = false;
        
        // Flag to know if we are in an abstract function declaration
        $inAbstract  = false;
        
        // Flag to know if we are in an eval() function
        $inEval      = false;
        
        // Flag to know if we are in a double quoted string declaration
        $inString    = false;
        
        // Block level, when inside a function
        $level       = 0;
        
        // Count for the local variables (when inside function)
        $varCount    = 0;
        
        // Number of processed tokens
        $tokenCount  = 0;
        
        // Process each token
        foreach( $tokens as $key => $token ) {
            
            // Checks if the token is an array
            if( is_array( $token ) ) {
                
                // Checks if we are declaring an abstract function
                if( $token[ 0 ] === T_ABSTRACT ) {
                    
                    $inAbstract = true;
                }
                
                // Nothing to do for abstract classes
                if( $inAbstract && $token[ 0 ] === T_CLASS ) {
                    
                    $inAbstract = false;
                }
                
                // Checks if we are declaring code that will be evaluated
                if( $token[ 0 ] === T_EVAL ) {
                    
                    $inEval = true;
                }
                
                // Checks if we are declaring a function
                if( $token[ 0 ] === T_FUNCTION ) {
                    
                    $inFunc      = true;
                    $funcGlobals = array();
                    $funcVars    = array();
                    $varCount    = 0;
                }
                
                // Checks if we are declaring global variables inside a function
                if( $inFunc && $token[ 0 ] === T_GLOBAL ) {
                    
                    $inGlobal = true;
                }
                
                // Checks if we are declaring a global variable
                if( $inGlobal && $token[ 0 ] === T_VARIABLE ) {
                    
                    $funcGlobals[ $token[ 1 ] ] = true;
                }
                
                // Checks if we are using a variable, and if it's local, so we can rename it
                if( $renameVariables
                    && $inFunc
                    && !$inGlobal
                    && $token[ 0 ] === T_VARIABLE
                    && !isset( self::$_superGlobals[ $token[ 1 ] ] )
                    && !isset( $funcGlobals[ $token[ 1 ] ] )
                    && $token[ 1 ] !== '$this'
                    && ( !is_array( $lastToken ) || $lastToken[ 0 ] !== T_PAAMAYIM_NEKUDOTAYIM )
                ) {
                    // Has the variable been renamed already?
                    if( isset( $funcVars[ $token[ 1 ] ] ) ) {
                        
                        // Yes, gets the sort name
                        $token[ 1 ] = $funcVars[ $token[ 1 ] ];
                        
                    } else {
                        
                        // No, generates a new name, and stores it
                        $varName                 = $this->_generateVarName( $varCount );
                        $funcVars[ $token[ 1 ] ] = '$' . $varName;
                        $token[ 1 ]              = '$' . $varName;
                        $varCount++;
                    }
                }
                
                // Checks if we are declaring code that has to be evaluated
                if( $renameVariables
                    && $inFunc
                    && $inEval
                    && $token[ 0 ] === T_CONSTANT_ENCAPSED_STRING
                ) {
                    
                    // Finds all variables in the evaluated code
                    $matches = array();
                    preg_match_all( '/[^:]*(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $token[ 1 ], $matches );
                    
                    // Checks if variables were found
                    if( is_array( $matches ) ) {
                        
                        // Sorts the variables
                        arsort($matches[ 1 ]);
                        
                        // Process each variable
                        foreach( $matches[ 1 ] as $var ) {
                            
                            // May we rename the variable?
                            if( !isset( self::$_superGlobals[ $var ] )
                                && !isset( $funcGlobals[ $var ] )
                                && $var !== '$this'
                            ) {
                                
                                // Has the variable been renamed already?
                                if( isset( $funcVars[ $var ] ) ) {
                                    
                                    // Yes, gets the sort name
                                    $token[ 1 ] = str_replace( $var, $funcVars[ $var ], $token[ 1 ] );
                                    
                                } else {
                                    
                                    // No, generates a new name, and stores it
                                    $varName                 = $this->_generateVarName( $varCount );
                                    $funcVars[ $token[ 1 ] ] = '$' . $varName;
                                    $token[ 1 ]              = str_replace( $var, $varName, $token[ 1 ] );
                                    $varCount++;
                                }
                            }
                        }
                    }
                }
                
                // Checks if we are inside a double quoted string and using a variable
                if( $inString && $token[ 0 ] === T_VARIABLE ) {
                    
                    // Checks if the variable is the first element of the double quoted string
                    if( !is_array( $lastToken ) && $lastToken === '\'' ) {
                        
                        // Removes the start of the string, and adds the variable with concatenation
                        array_pop( $codeLines );
                        $tokenCount--;
                        $token[ 1 ] = $token[ 1 ] . ' . \'';
                        
                    } else {
                        
                        // Use concatenation
                        $token[ 1 ] = '\' . ' . $token[ 1 ] . ' . \'';
                    }
                }
                
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
                
                // PHP open tag normalization
                if( $token[ 0 ] === T_OPEN_TAG ) {
                    
                    // Ensures we are not using ASP tags, nor short tags, and removes un-needed whitespaces
                    $token[ 1 ] = '<?php ';
                }
                
                // PHP open tag with echo normalization
                if( $token[ 0 ] === T_OPEN_TAG_WITH_ECHO ) {
                    
                    // Adds a normalized PHP tag, and adds a call to the echo() function
                    $token[ 1 ] = '<?php echo ';
                }
                
                // Converts all numbers to their decimal value
                if( $token[ 0 ] === T_LNUMBER ) {
                    
                    // Detects hexadecimal or octal notations
                    if( substr( $token[ 1 ], 0, 2 ) === '0x' ) {
                        
                        // Hexadecimal
                        $token[ 1 ] = hexdec( $token[ 1 ] );
                        
                    } elseif( substr( $token[ 1 ], 0, 1 ) === '0' ) {
                        
                        // Octal
                        $token[ 1 ] = octdec( $token[ 1 ] );
                    }
                }
                
                // Do not keep a whitespace after a string
                if( $token[ 0 ] === T_WHITESPACE
                    && is_array( $lastToken )
                    && $lastToken[ 0 ] === T_CONSTANT_ENCAPSED_STRING
                ) {
                    continue;
                }
                
                // Removes whitespace before a string
                if( $token[ 0 ] === T_CONSTANT_ENCAPSED_STRING
                    && is_array( $lastToken )
                    && $lastToken[ 0 ] === T_WHITESPACE
                ) {
                    array_pop( $codeLines );
                    $tokenCount--;
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
                    $tokenCount--;
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
                    $tokenCount--;
                }
                
                // Stores the code for the current token
                $codeLines[] = $token[ 1 ];
                
                // Removes spaces on a cast
                if( $token[ 0 ] === T_ARRAY_CAST
                    || $token[ 0 ] === T_BOOL_CAST
                    || $token[ 0 ] === T_DOUBLE_CAST
                    || $token[ 0 ] === T_INT_CAST
                    || $token[ 0 ] === T_OBJECT_CAST
                    || $token[ 0 ] === T_STRING_CAST
                    || $token[ 0 ] === T_UNSET_CAST
                ) {
                    $token[ 1 ] = str_replace( ' ', '', $token[ 1 ] );
                }
                
            } else {
                
                // Checks for the end of the global variables declaration
                if( $inGlobal && $token === ';' ) {
                    
                    $inGlobal = false;
                }
                
                // Checks for the end of the eval() declaration
                if( $inEval && $token === ';' ) {
                    
                    $inEval = false;
                }
                
                // Checks for the end of an abstract function declaration
                if( $inAbstract && $inFunc && $token === ';' ) {
                    
                    $inAbstract = false;
                    $inFunc     = false;
                }
                
                // If inside a function, detect the start of a code block
                if( $inFunc && $token === '{' ) {
                    
                    $level++;
                }
                
                // If inside a function, detect the start of a code block
                if( $inFunc && $token === '}' ) {
                    
                    $level--;
                    
                    // Checks the code block level
                    if( $level === 0 ) {
                        
                        // End of the current function
                        $inFunc = false;
                        $level  = 0;
                    }
                }
                
                // Checks if we are in a double quoted string declaration
                if( $token === '"' ) {
                    
                    // Replaces by a single quote
                    $token = '\'';
                    
                    // Beginning or end of the double quoted string?
                    if( $inString ) {
                        
                        // Checks if the last token was a variable inside the string
                        if( is_array( $lastToken ) && $lastToken[ 0 ] === T_VARIABLE ) {
                            
                            // Removes the last concatenation
                            $codeLines[ $tokenCount - 1 ] = substr( $codeLines[ $tokenCount - 1 ], 0, -4 );
                            $token                  = '';
                        }
                        
                        // End of the string
                        $inString = false;
                        
                    } else {
                        
                        // Beginning of the string
                        $inString = true;
                    }
                }
                
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
                    ||   $token === ':' 
                    ||   $token === ';' 
                    ||   $token === '+' 
                    ||   $token === '-' 
                    ||   $token === '/' 
                    ||   $token === '%' 
                    ||   $token === '>' 
                    ||   $token === '<' 
                    ||   $token === '>>'
                    ||   $token === '<<'
                    ||   $token === '++'
                    ||   $token === '--'
                    ||   $token === '!'
                    ||   $token === ',' )
                ) {
                    array_pop( $codeLines );
                    $tokenCount--;
                }
                
                // Stores the code
                $codeLines[] = $token;
            }
            
            // Stores the last token
            $lastToken = $token;
            
            // Increases the token count
            $tokenCount++;
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
    
    /**
     * Generates the shortest variable name, with a-z and A-Z
     * 
     * @param   int     The number of the variable
     * @return  string  The name of the variable
     */
    protected function _generateVarName( $int )
    {
        // Checks if we'll have to use more that a character
        if( $int < 52 ) {
            
            // Single character
            return self::$_varNameChars[ $int % 52 ];
            
        } else {
            
            // Multiple characters
            return $this->_generateVarName( ( $int / 52 ) - 1 ) . self::$_varNameChars[ $int % 52 ];
        }
    }
}
