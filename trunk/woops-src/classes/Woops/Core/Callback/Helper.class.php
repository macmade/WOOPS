<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Callback helper class
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Callback
 */
class Woops_Core_Callback_Helper
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * THe PHP callback
     */
    protected $_callback         = false;
    
    /**
     * Wheter the callback returns a reference
     */
    protected $_returnsReference = false;
    
    /**
     * Class sonstructor
     * 
     * @param   callback    The PHP callback
     * @return  void
     */
    public function __construct( $callback )
    {
        // Ensures the callback is valid
        if( !is_callable( $callback ) ) {
            
            // Error - The callback is not valid
            throw new Woops_Core_Callback_Helper_Exception(
                'Invalid PHP callback',
                Woops_Core_Callback_Helper_Exception::EXCEPTION_INVALID_CALLBACK
            );
        }
        
        // Stores the callback
        $this->_callback = $callback;
        
        // Checks the callback type
        if( is_array( $this->_callback ) ) {
            
            // Checks if the callback returns a reference
            $ref                     = Woops_Core_Reflection_Method::getInstance( $callback[ 0 ], $callback[ 1 ] );
            $this->_returnsReference = $ref->returnsReference();
            
        } else {
            
            // Checks if the callback returns a reference
            $ref                     = Woops_Core_Reflection_Function::getInstance( $callback );
            $this->_returnsReference = $ref->returnsReference();
        }
    }
    
    /**
     * Invokes a callback
     * 
     * This method is used to avoid having to call the call_user_func_array()
     * function, which is slow and may have problems dealing with references.
     * 
     * @param   array   The arguments to pass to the callback
     * @return  mixed   The return value of the callback
     */
    public function &invoke( array $args = array() )
    {
        // Gets the number of arguments to pass to the callbak
        $argsCount = count( $args );
        
        // Checks if the callback is an array
        if( is_array( $this->_callback ) ) {
            
            // Checks if we need to make a member or a static call
            if( is_object( $this->_callback[ 0 ] ) ) {
                
                // Gets the object and the method to use
                $object = $this->_callback[ 0 ];
                $method = $this->_callback[ 1 ];
                
                // Checks if we have to return a reference or not
                if( $this->_returnsReference ) {
                    
                    // Checks the number of arguments
                    // This will avoid a call to eval() if the number of arguments is lower than ten
                    switch( $argsCount ) {
                        
                        case 0:
                            
                            $return =& $object->$method();
                            break;
                            
                        case 1:
                            
                            $return =& $object->$method( $args[ 0 ] );
                            break;
                            
                        case 2:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ] );
                            break;
                            
                        case 3:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                            break;
                            
                        case 4:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                            break;
                            
                        case 5:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                            break;
                            
                        case 6:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ] );
                            break;
                            
                        case 7:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ] );
                            break;
                            
                        case 8:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ] );
                            break;
                            
                        case 9:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ] );
                            break;
                            
                        case 10:
                            
                            $return =& $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ], $args[ 9 ] );
                            break;
                            
                        // More than ten arguments - We'll use eval() as call_user_func_array cannot return references
                        default:
                            
                            eval( '$return =& $object->$method( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                    }
                    
                } else {
                    
                    // Checks the number of arguments
                    // This will avoid a call to eval() if the number of arguments is lower than ten
                    switch( $argsCount ) {
                        
                        case 0:
                            
                            $return = $object->$method();
                            break;
                            
                        case 1:
                            
                            $return = $object->$method( $args[ 0 ] );
                            break;
                            
                        case 2:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ] );
                            break;
                            
                        case 3:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                            break;
                            
                        case 4:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                            break;
                            
                        case 5:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                            break;
                            
                        case 6:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ] );
                            break;
                            
                        case 7:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ] );
                            break;
                            
                        case 8:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ] );
                            break;
                            
                        case 9:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ] );
                            break;
                            
                        case 10:
                            
                            $return = $object->$method( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ], $args[ 9 ] );
                            break;
                            
                        // More than ten arguments - We'll use eval() as call_user_func_array cannot return references
                        default:
                            
                            eval( '$return = $object->$method( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                    }
                }
                
            } else {
                
                // Checks if we have to return a reference or not
                if( $this->_returnsReference ) {
                    
                    // We'll use eval as late static bindings are only available since PHP 5.3
                    eval( '$return = ' . $this->_callback[ 0 ] . '::' . $this->_callback[ 1 ] . '( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                    
                } else {
                    
                    // We'll use eval as late static bindings are only available since PHP 5.3
                    eval( '$return = ' . $this->_callback[ 0 ] . '::' . $this->_callback[ 1 ] . '( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                }
            }
            
        } else {
            
            // Name of the function to call
            $function = $this->_callback;
            
            // Checks if we have to return a reference or not
            if( $this->_returnsReference ) {
                
                // Checks the number of arguments
                // This will avoid a call to eval() if the number of arguments is lower than ten
                switch( $argsCount ) {
                    
                    case 0:
                        
                        $return =& $function();
                        break;
                        
                    case 1:
                        
                        $return =& $function( $args[ 0 ] );
                        break;
                        
                    case 2:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ] );
                        break;
                        
                    case 3:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                        break;
                        
                    case 4:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                        break;
                        
                    case 5:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                        break;
                        
                    case 6:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ] );
                        break;
                        
                    case 7:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ] );
                        break;
                        
                    case 8:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ] );
                        break;
                        
                    case 9:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ] );
                        break;
                        
                    case 10:
                        
                        $return =& $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ], $args[ 9 ] );
                        break;
                        
                    // More than ten arguments - We'll use eval() as call_user_func_array cannot return references
                    default:
                        
                        eval( '$return =& $function( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                }
                
            } else {
                
                // Checks the number of arguments
                // This will avoid a call to eval() if the number of arguments is lower than ten
                switch( $argsCount ) {
                    
                    case 0:
                        
                        $return = $function();
                        break;
                        
                    case 1:
                        
                        $return = $function( $args[ 0 ] );
                        break;
                        
                    case 2:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ] );
                        break;
                        
                    case 3:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                        break;
                        
                    case 4:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                        break;
                        
                    case 5:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                        break;
                        
                    case 6:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ] );
                        break;
                        
                    case 7:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ] );
                        break;
                        
                    case 8:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ] );
                        break;
                        
                    case 9:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ] );
                        break;
                        
                    case 10:
                        
                        $return = $function( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ], $args[ 5 ], $args[ 6 ], $args[ 7 ], $args[ 8 ], $args[ 9 ] );
                        break;
                        
                    // More than ten arguments - We'll use eval() as call_user_func_array cannot return references
                    default:
                        
                        eval( '$return = $function( $args[ ' . implode( ' ], $args[ ', array_keys( $args ) ) . ' ] );' );
                }
            }
        }
        
        // Returns the return value of the callback
        return $return;
    }
}
