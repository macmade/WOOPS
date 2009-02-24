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

# $Id$

/**
 * Abstract class for the lexers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Lexical
 */
abstract class Woops_Lexical_Analyser
{
    /**
     * 
     */
    private static $_lexers         = array();
    
    /**
     * 
     */
    private static $_tokensByNames  = array();
    
    /**
     * 
     */
    private static $_tokensByCode   = array();
    
    /**
     * 
     */
    private static $_tokensByValues = array();
    
    /**
     * 
     */
    private static $_tokensByChar   = array();
    
    /**
     * 
     */
    private $_lexer                 = '';
    
    /**
     * 
     */
    protected $_data                = '';
    
    /**
     * 
     */
    protected $_dataLength          = 0;
    
    /**
     * 
     */
    protected $_dataOffset          = 0;
     
    /**
     * 
     */
    public function __construct()
    {
        $this->_lexer = get_class( $this );
        
        if( !isset( self::$_lexers[ $this->_lexer ] ) ) {
            
            self::_initLexer( $this->_lexer );
        }
    }
    
    /**
     * 
     */
    private static function _initLexer( $class )
    {
        $reflection   = Woops_Core_Reflection_Class::getInstance( $class );
        $constants    = $reflection->getConstants();
        $staticProps  = $reflection->getStaticProperties();
        
        if( !isset( $staticProps[ '_tokens' ] ) ) {
            
            throw new Woops_Lexical_Analyser_Exception(
                '',
                Woops_Lexical_Analyser_Exception::EXCEPTION_NO_TOKENS
            );
        }
        
        self::$_lexers[ $class ]         = true;
        self::$_tokensByNames[ $class ]  = array();
        self::$_tokensByCode[ $class ]   = array();
        self::$_tokensByValues[ $class ] = array();
        self::$_tokensByChar[ $class ]   = array();
        
        foreach( $staticProps[ '_tokens' ] as $name => $str ) {
            
            if( !isset( $constants[ $name ] ) ) {
                
                throw new Woops_Lexical_Analyser_Exception(
                    '',
                    Woops_Lexical_Analyser_Exception::EXCEPTION_NO_TOKEN_CONSTANT
                );
            }
            
            $code = $constants[ $name ];
            
            if( isset( self::$_tokensByCode[ $class ][ $code ] ) ) {
                
                throw new Woops_Lexical_Analyser_Exception(
                    '',
                    Woops_Lexical_Analyser_Exception::EXCEPTION_DUPLICATE_TOKEN_CODE
                );
            }
            
            self::$_tokensByNames[ $class ][ $name ] = $code;
            self::$_tokensByCode[ $class ][ $code ]  = $name;
            self::$_tokensByValues[ $class ][ $str ] = $code;
            
            $strLen  =  strlen( $str );
            $storage =& self::$_tokensByChar[ $class ];
            
            for( $i = 0; $i < $strLen; $i++ ) {
                
                if( !isset( $storage[ $str[ $i ] ] ) ) {
                    
                    $storage[ $str[ $i ] ] = array();
                }
                
                $storage =& $storage[ $str[ $i ] ];
            }
        }
    }
    
    /**
     * 
     */
    protected function _readChar()
    {
        if( $this->_dataOffset >= $this->_dataLength ) {
            
            return false;
        }
        
        $char = $this->_data[ $this->_dataOffset ];
        $this->_dataOffset++;
        
        return $char;
    }
    
    /**
     * 
     */
    public function tokenize( $data )
    {
        $tokens            =  array();
        $lastToken         =  false;
        $tokenCount        =  0;
        
        $this->_data       =  $data;
        $this->_dataLength =  strlen( $data );
        
        $byChar            =& self::$_tokensByChar[   $this->_lexer ];
        $byCode            =& self::$_tokensByCode[   $this->_lexer ];
        $byValue           =& self::$_tokensByValues[ $this->_lexer ];
        
        while( ( $char = $this->_readChar() ) !== false ) {
            
            if( isset( $byChar[ $char ] ) ) {
                
                $charPos = $byChar[ $char ];
                $token   = $char;
                
                while( ( $nextChar = $this->_readChar() ) !== false && isset( $charPos[ $nextChar ] ) ) {
                    
                    $token  .=  $nextChar;
                    $charPos =& $charPos[ $nextChar ];
                }
                
                if( isset( $byValue[ $token ] ) ) {
                     
                    $tokens[] = array(
                        $token,
                        $byValue[ $token ],
                        $byCode[ $byValue[ $token ] ]
                    );
                    
                    $tokenCount++;
                    
                } elseif( $tokenCount && !is_array( $lastToken ) ) {
                    
                    $tokens[ $tokenCount - 1 ] .= $char;
                    
                } else {
                    
                    $tokens[] = $token;
                    $tokenCount++;
                }
                
                $this->_dataOffset--;
                
            } elseif( $tokenCount && !is_array( $lastToken ) ) {
                
                $tokens[ $tokenCount - 1 ] .= $char;
                
            } else {
                
                $tokens[] = $char;
                $tokenCount++;
            }
            
            $lastToken = $tokens[ $tokenCount - 1 ];
        }
        
        return $tokens;
    }
}
