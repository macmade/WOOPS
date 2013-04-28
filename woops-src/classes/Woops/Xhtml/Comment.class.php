<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Class to create XHTML comments
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Xhtml
 */
class Woops_Xhtml_Comment extends Woops_Xhtml_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The text of the comment
     */
    protected $_comment = '';
    
    /**
     * Class constructor
     * 
     * @return  void
     * @see     Woops_Xhtml_Tag::__construct
     */
    public function __construct( $text )
    {
        // Sets the comment text
        $this->_comment = $text;
        
        // Calls the parent constructor
        parent::__construct( '' );
    }
    
    /**
     * Returns the HTML comment
     * 
     * @param   boolean Whether the output must be XML compliant
     * @param   int     The indentation level
     * @return  string  The HTML comment, if $xmlCompliant is false, otherwise a blank string
     */
    protected function _output( $xmlCompliant = false, $level = 0 )
    {
        // Checks if the output must be XML compliant
        if( !$xmlCompliant ) {
            
            // Gets the indent level
            $indent = str_pad( '', $level, self::$_str->TAB );
            
            // Support the multiline comments
            if( strchr( $this->_comment, self::$_str->NL ) ) {
                
                // Starts the comment
                $out  = self::$_str->NL . $indent . '<!-- ' . self::$_str->NL . $indent . self::$_str->NL;
                
                // Adds the multiline comment text
                $out .= $indent . str_replace( self::$_str->NL, self::$_str->NL . $indent, $this->_comment );
                
                // Ends the comment
                $out .= self::$_str->NL . $indent . self::$_str->NL . $indent . '-->' . self::$_str->NL;
                
                // Returns the multiline comment
                return $out;
                
            } else {
                
                // Returns the single line comment
                return self::$_str->NL . $indent . '<!-- ' . $this->_comment . ' -->' . self::$_str->NL . $indent;
            }
        }
        
        // Do not return the HTML comment when the output must be XML compliant
        return '';
    }
}
