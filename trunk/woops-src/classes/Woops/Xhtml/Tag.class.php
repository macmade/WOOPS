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
 * XHTML writer class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xhtml
 */
class Woops_Xhtml_Tag implements ArrayAccess, Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
     * The list of the XHTML empty tags (as in the XHTML 1.0 Strict DTD)
     */
    protected static $_emptyTags      = array(
        'area'  => true,
        'base'  => true,
        'br'    => true,
        'col'   => true,
        'img'   => true,
        'input' => true,
        'hr'    => true,
        'link'  => true,
        'meta'  => true,
        'param' => true
    );
    
    /**
     * The name of the current tag
     */
    protected $_tagName                = '';
    
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
    protected $_hasNodeChildren        = false;
    
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
        return $this->asHtml();
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
    public function __call( $name, array $args = array() )
    {
        switch( $name ) {
            
            case 'spacer':
                
                return $this->_addSpacer( $args[ 0 ] );
                break;
            
            case 'comment':
                
                return $this->_addComment( $args[ 0 ] );
                break;
        }
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
     * @return  NULL
     */
    public function rewind()
    {
        $this->_iteratorIndex = 0;
    }
    
    /**
     * Returns the current tag (SPL Iterator method)
     * 
     * @return  Woops_Xhtml_Tag The current HTML tag object
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
        return $this->_children[ $this->_iteratorIndex ]->_tagName;
    }
    
    /**
     * Moves the position to the next tag (SPL Iterator method)
     * 
     * @return  NULL
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
     * @return  NULL
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str             = Woops_String_Utils::getInstance();
        
        // Gets the instance of the configuration object
        self::$_conf            = Woops_Core_Config_Getter::getInstance();
        
        // Sets the XHTML formatting option
        self::$_formattedOutput = ( boolean )self::$_conf->getVar( 'xhtml', 'format' );
        
        // Static variables are set
        self::$_hasStatic       = true;
    }
    
    /**
     * 
     */
    protected function _addSpacer( $pixels )
    {
        $spacer            = $this->_addChild( 'div' );
        $spacer[ 'class' ] = 'spacer';
        $spacer[ 'style' ] = 'margin-top: ' . $pixels . 'px';
        return $spacer;
    }
    
    /**
     * 
     */
    protected function _addComment( $text )
    {
        if( !isset( $this->_childrenByName[ '<!--' ] ) ) {
            
            $this->_childrenByName[ '<!--' ]      = array();
            $this->_childrenCountByName[ '<!--' ] = 0;
        }
        
        $comment             = new Woops_Xhtml_Comment( $text );
        $comment->_parents[] = $this;
        
        $this->_children[]                 = $comment;
        $this->_childrenByName[ '<!--' ][] = array( $this->_childrenCount, $comment );
        
        $this->_childrenCountByName[ '<!--' ]++;
        $this->_childrenCount++;
        
        $this->_hasNodeChildren = true;
        
        return $comment;
    }
    
    /**
     * 
     */
    protected function _addChild( $name )
    {
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
        
        $this->_hasNodeChildren = true;
        
        return $child;
    }
    
    /**
     * Returns the output of the current tag
     * 
     * @param   boolean Wheter the output must be XML compliant
     * @param   int     The indentation level
     * @return  string  The output of the current tag (tag name and content)
     */
    protected function _output( $xmlCompliant = false, $level = 0 )
    {
        // Starts the tag
        $tag = '<' . $this->_tagName;
        
        // Process each registered attribute
        foreach( $this->_attribs as $key => &$value ) {
            
            // Adds the current attribute
            $tag .= ' ' . $key . '="' . $value . '"';
        }
        
        // Checks if we children to display
        if( !$this->_childrenCount ) {
            
            // No - Checks if the tag is self closed
            $tag .= ( isset( self::$_emptyTags[ $this->_tagName ] ) || $xmlCompliant ) ? ' />' : '></' . $this->_tagName . '>';
            
        } else {
            
            // Ends the start tag
            $tag .= '>';
            
            // Process each children
            foreach( $this->_children as $child ) {
                
                // Checks the current child is a tag or a string
                if( $child instanceof self ) {
                    
                    // Checks if we have to format the output
                    if( self::$_formattedOutput ) {
                        
                        // Adds the current child
                        $tag .= self::$_str->NL . str_pad( '', $level + 1, self::$_str->TAB );
                        $tag .= $child->_output( $xmlCompliant, $level + 1 );
                        
                    } else {
                        
                        // Adds the current child
                        $tag .= $child->_output( $xmlCompliant, $level + 1 );
                    }
                    
                } elseif( $xmlCompliant ) {
                    
                    // If we must be XML compliant, nodes and data are not allwed in a single node
                    if( $this->_hasNodeChildren ) {
                        
                        // Protect the data with CDATA, and adds a span tag for the XML compliancy
                        $tag .= '<span><![CDATA[' . trim( $child ) . ']]></span>';
                        
                    } else {
                        
                        // Protects the data with CDATA
                        $tag .= '<![CDATA[' . trim( $child ) . ']]>';
                    }
                    
                } else {
                    
                    // String - Adds the child data
                    $tag .= trim( ( string )$child );
                }
            }
            
            // Checks if we have to format the output
            if( self::$_formattedOutput && $this->_hasNodeChildren ) {
                
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
    public function addChildNode( Woops_Xhtml_Tag $child )
    {
        if( !isset( self::$_emptyTags[ $this->_tagName ] ) ) {
            
            if( !isset( $this->_childrenByName[ $child->_tagName ] ) ) {
                
                $this->_childrenByName[ $child->_tagName ]      = array();
                $this->_childrenCountByName[ $child->_tagName ] = 0;
            }
            
            $child->_parents[] = $this;
            
            $this->_children[]                           = $child;
            $this->_childrenByName[ $child->_tagName ][] = array( $this->_childrenCount, $child );
            
            $this->_childrenCountByName[ $child->_tagName ]++;
            $this->_childrenCount++;
            
            $this->_hasNodeChildren = true;
            
            return $child;
        }
        
        return NULL;
    }
    
    /**
     * 
     */
    public function addTextData( $data )
    {
        if( !isset( self::$_emptyTags[ $this->_tagName ] ) ) {
            
            if( $data instanceof self ) {
                
                $this->addChildNode( $data );
                
            } else {
                
                if( $this->_childrenCount
                    && !( $this->_children[ $this->_childrenCount - 1 ] instanceof self )
                ) {
                    
                    $this->_children[ $this->_childrenCount - 1 ] .= $data;
                    
                } else {
                    
                    $this->_children[] = ( string )$data;
                    $this->_childrenCount++;
                }
            }
        }
    }
    
    /**
     * 
     */
    public function asHtml()
    {
        return $this->_output( false );
    }
    
    /**
     * 
     */
    public function asXml()
    {
        return $this->_output( true );
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
        if( isset( $this->_childrenByName[ $name ] ) ) {
            
            if( $index === -1 ) {
                
                $index = $this->_childrenCountByName[ $name ] - 1;
            }
            
            if( isset( $this->_childrenByName[ $name ][ $index ] ) ) {
                
                return $this->_childrenByName[ $name ][ $index ][ 1 ];
            }
        }
        
        return NULL;
    }
    
    public function removeTag( $name, $index = 0 )
    {
        if( isset( $this->_childrenByName[ $name ] ) ) {
            
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
                    
                    $this->_hasNodeChildren = false;
                }
            }
        }
    }
    
    /**
     * 
     */
    public function removeAllTags()
    {
        if( $this->_childrenCount > 0 ) {
              
            $this->_children            = array();
            $this->_childrenByName      = array();
            $this->_childrenCountByName = array();
            $this->_childrenCount       = 0;
            $this->_hasNodeChildren     = false;
        }
    }
}
