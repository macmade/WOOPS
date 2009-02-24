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
 * XML writer class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xml
 */
class Woops_Xml_Tag implements ArrayAccess, Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    const TYPE_NODE = 0x01;
    
    /**
     * 
     */
    const TYPE_DATA = 0x02;
    
    /**
     * Wether the output is formatted or not
     */
    protected static $_formattedOutput = true;
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic         = false;
    
    /**
     * The instance of the string utilities
     */
    protected static $_str             = NULL;
    
    /**
     * The WOOPS cpnfiguration object
     */
    private static $_conf              = NULL;
    
    /**
     * 
     */
    protected $_type                   = 0;
    
    /**
     * The name of the current tag
     */
    protected $_tagName                = '';
    
    /**
     * 
     */
    protected $_data                   = '';
    
    /**
     * The attributes of the current tag
     */
    protected $_attribs                = array();
    
    /**
     * 
     */
    protected $_children               = array();
    
    /**
     * 
     */
    protected $_childrenByName         = array();
    
    /**
     * 
     */
    protected $_childrenCountByName    = array();
    
    /**
     * 
     */
    protected $_childrenCount          = 0;
    
    /**
     * 
     */
    protected $_parents                = array();
    
    /**
     * The current position for the SPL Iterator methods
     */
    protected $_iteratorIndex          = 0;
    
    /**
     * 
     */
    public function __construct( $tagName )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Sets the tag name
        $this->_tagName = ( string )$tagName;
    }
    
    /**
     * 
     */
    public function __toString()
    {
        return $this->asXml();
    }
    
    /**
     * 
     */
    public function __set( $name, $value )
    {
        $this->_addChild( $name )->addTextData( $value );
    }
    
    /**
     * 
     */
    public function __get( $name )
    {
        return $this->_addChild( $name );
    }
    
    /**
     * 
     */
    public function offsetExists( $offset )
    {
        return isset( $this->_attribs[ $offset ] );
    }
    
    /**
     * 
     */
    public function offsetGet( $offset )
    {
        return $this->_attribs[ $offset ];
    }
    
    /**
     * 
     */
    public function offsetSet( $offset, $value )
    {
        $this->_attribs[ $offset ] = ( string )$value;
    }
    
    /**
     * 
     */
    public function offsetUnset( $offset )
    {
        unset( $this->_attribs[ $offset ] );
    }
    
    /**
     * Moves the position to the first tag (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorIndex = 0;
    }
    
    /**
     * Returns the current tag (SPL Iterator method)
     * 
     * @return  Woops_Xml_Tag   The current XML tag object
     */
    public function current()
    {
        return $this->_children[ $this->_iteratorIndex ];
    }
    
    /**
     * Gets the tag name for the current tag (SPL Iterator method)
     * 
     * @return  int     The name of the current tag
     */
    public function key()
    {
        if( $this->_children[ $this->_iteratorIndex ] instanceof self ) {
            
            return $this->_children[ $this->_iteratorIndex ]->_tagName;
            
        } else {
            
            return '';
        }
    }
    
    /**
     * Moves the position to the next tag (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorIndex++;
    }
    
    /**
     * Checks for a current tag (SPL Iterator method)
     * 
     * @return  boolean
     */
    public function valid()
    {
        return isset( $this->_children[ $this->_iteratorIndex ] );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str             = Woops_String_Utils::getInstance();
        
        // Gets the instance of the configuration object
        self::$_conf            = Woops_Core_Config_Getter::getInstance();
        
        // Sets the XML formatting option
        self::$_formattedOutput = ( boolean )self::$_conf->getVar( 'xml', 'format' );
        
        // Static variables are set
        self::$_hasStatic       = true;
    }
    
    /**
     * 
     */
    protected function _addChild( $name )
    {
        if( $this->_type === self::TYPE_DATA ) {
            
            throw new Woops_Xml_Tag_Exception(
                'Cannot add a node in the current tag as it already contains text data',
                Woops_Xml_Tag_Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        $this->_type = self::TYPE_NODE;
        
        if( !isset( $this->_childrenByName[ $name ] ) ) {
            
            $this->_childrenByName[ $name ]      = array();
            $this->_childrenCountByName[ $name ] = 0;
        }
        
        $child             = new self( $name );
        $child->_parents[] = $this;
        
        $this->_children[]                = $child;
        $this->_childrenByName[ $name ][] = array( $this->_childrenCount, $child );
        
        $this->_childrenCountByName[ $name ]++;
        $this->_childrenCount++;
        
        return $child;
    }
    
    /**
     * Returns the output of the current tag
     * 
     * @param   int     The indentation level
     * @return  string  The output of the current tag (tag name and content)
     */
    protected function _output( $level = 0 )
    {
        // Starts the tag
        $tag = '<' . $this->_tagName;
        
        // Process each registered attribute
        foreach( $this->_attribs as $key => &$value ) {
            
            // Adds the current attribute
            $tag .= ' ' . $key . '="' . $value . '"';
        }
        
        // Checks if we have something to display in the tag
        if( (    $this->_type === self::TYPE_NODE && !$this->_childrenCount )
            || ( $this->_type === self::TYPE_DATA && !$this->_data )
        ) {
            
            // No - Checks if the tag is self closed
            $tag .= ' />';
            
        } else {
            
            // Ends the start tag
            $tag .= '>';
            
            // Checks the tag type
            if( $this->_type === self::TYPE_NODE ) {
                
                // Process each children
                foreach( $this->_children as $child ) {
                    
                    // Checks if we have to format the output
                    if( self::$_formattedOutput ) {
                        
                        // Adds the current child
                        $tag .= self::$_str->NL . str_pad( '', $level + 1, self::$_str->TAB );
                        $tag .= $child->_output( $level + 1 );
                        
                    } else {
                        
                        // Adds the current child
                        $tag .= $child->_output( $level + 1 );
                    }
                }
                
            } else {
                
                // Protects the data with CDATA if necessary
                $data = ( strstr( $this->_data, '&' ) || strstr( $this->_data, '<' ) ) ? '<![CDATA[' . trim( ( string )$this->_data ) . ']]>' : trim( ( string )$this->_data );
                
                // Adds the data
                $tag .= $data;
            }
            
            // Checks if we have to format the output
            if( self::$_formattedOutput && $this->_type === self::TYPE_NODE ) {
                
                // Adds a new line and the current indentation
                $tag .= self::$_str->NL . str_pad( '', $level, self::$_str->TAB );
            }
            
            // Closes the tag
            $tag .= '</' . $this->_tagName . '>';
        }
        
        // Returns the tag
        return $tag;
    }
    
    /**
     * 
     */
    public function addChildNode( Woops_Xml_Tag $child )
    {
        if( $this->_type === self::TYPE_DATA ) {
            
            throw new Woops_Xml_Tag_Exception(
                'Cannot add a node in the current tag as it already contains text data',
                Woops_Xml_Tag_Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        $this->_type = self::TYPE_NODE;
        
        if( !isset( $this->_childrenByName[ $child->_tagName ] ) ) {
            
            $this->_childrenByName[ $child->_tagName ]      = array();
            $this->_childrenCountByName[ $child->_tagName ] = 0;
        }
        
        $child->_parents[] = $this;
        
        $this->_children[]                           = $child;
        $this->_childrenByName[ $child->_tagName ][] = array( $this->_childrenCount, $child );
        
        $this->_childrenCountByName[ $child->_tagName ]++;
        $this->_childrenCount++;
        
        return $child;
    }
    
    /**
     * 
     */
    public function addTextData( $data )
    {
        if( $this->_type === self::TYPE_NODE ) {
            
            throw new Woops_Xml_Tag_Exception(
                'Cannot add text data in the current tag as it already contains nodes',
                Woops_Xml_Tag_Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        $this->_type  = self::TYPE_DATA;
        
        $this->_data .= ( string )$data;
    }
    
    /**
     * 
     */
    public function setTextData( $data )
    {
        if( $this->_type === self::TYPE_NODE ) {
            
            throw new Woops_Xml_Tag_Exception(
                'Cannot set text data in the current tag as it already contains nodes',
                Woops_Xml_Tag_Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        $this->_type = self::TYPE_DATA;
        
        $this->_data = ( string )$data;
    }
    
    /**
     * 
     */
    public function asXml()
    {
        return $this->_output();
    }
    
    /**
     * 
     */
    public function getParent( $parentIndex = 0 )
    {
        if( isset( $this->_parents[ $parentIndex ] ) ) {
            
            return $this->_parents[ $parentIndex ];
        }
        
        return NULL;
    }
    
    /**
     * 
     */
    public function getTag( $name, $index = 0 )
    {
        if( $this->_type === self::TYPE_NODE
            && isset( $this->_childrenByName[ $name ] )
        ) {
            
            if( $index === -1 ) {
                
                $index = $this->_childrenCountByName[ $name ] - 1;
            }
            
            if( isset( $this->_childrenByName[ $name ][ $index ] ) ) {
                
                return $this->_childrenByName[ $name ][ $index ][ 1 ];
            }
        }
        
        return NULL;
    }
    
    /**
     * 
     */
    public function removeTag( $name, $index = 0 )
    {
        if( $this->_type === self::TYPE_NODE
            && isset( $this->_childrenByName[ $name ] )
        ) {
            
            if( $index === -1 ) {
                
                $index = $this->_childrenCountByName[ $name ] - 1;
            }
            
            if( isset( $this->_childrenByName[ $name ][ $index ] ) ) {
                
                unset( $this->_children[ $this->_childrenByName[ $name ][ $index ][ 0 ] ] );
                unset( $this->_childrenByName[ $name ][ $index ] );
                
                $this->_childrenCount--;
                $this->_childrenCountByName[ $name ]--;
                
                if( !count( $this->_childrenCountByName[ $name ] ) ) {
                    
                    unset( $this->_childrenCountByName[ $name ] );
                }
                
                if( !count( $this->_childrenCountByName ) ) {
                    
                    $this->_type = 0;
                }
            }
        }
    }
    
    /**
     * 
     */
    public function removeAllTags()
    {
        if( $this->_type === self::TYPE_NODE ) {
              
            $this->_children            = array();
            $this->_childrenByName      = array();
            $this->_childrenCountByName = array();
            $this->_childrenCount       = 0;
            $this->_type                = 0;
        }
    }
    
    /**
     * 
     */
    public function removeAllAttributes()
    {
        $this->_attribs = array();
    }
    
    /**
     * 
     */
    public function getTagName()
    {
        return $this->_tagName;
    }
}
