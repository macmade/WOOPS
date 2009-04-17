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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF SymbolClass tag
 * 
 * The SymbolClass tag creates associations between symbols in the SWF file and
 * ActionScript3.0 classes. It is the ActionScript 3.0 equivalent of the
 * ExportAssets tag. If the character ID is zero, the class is associated with
 * the main timeline of the SWF. This is how the root class of a SWF is
 * designated. Classes listed in the SymbolClass tag are available for creation
 * by other SWF files (see StartSound2, DefineEditText (HasFontClass), and
 * PlaceObject3 (PlaceFlagHasClassName and PlaceFlagHasImage). For example, ten
 * SWF files that are all part of the same website can share an embedded custom
 * font if one file embeds and exports the font class.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class Woops_Swf_Tag_SymbolClass extends Woops_Swf_Tag implements Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type        = 0x4C;
    
    /**
     * The symbols
     */
    protected $_symbols     = array();
    
    /**
     * The ID of the symbols
     */
    protected $_symbolIds   = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Gets the current symbol name (SPL Iterator method)
     * 
     * @return  string  The current symbol name
     */
    public function current()
    {
        return $this->_symbols[ $this->_symbolIds[ $this->_iteratorPos ] ];
    }
    
    /**
     * Moves to the next tag object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current tag object (SPL Iterator method)
     * 
     * @return  int     The index of the current SWF tag
     */
    public function key()
    {
        return $this->_symbolIds[ $this->_iteratorPos ];
    }
    
    /**
     * Checks if there is a next tag object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next SWF tag, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_symbolIds );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Resets the symbols arrays
        $this->_symbols   = array();
        $this->_symbolIds = array();
        
        // Gets the number of symbols
        $symbolsNum     = $stream->littleEndianUnsignedShort();
        
        // Process each symbols
        for( $i = 0; $i < $symbolsNum; $i++ ) {
            
            // Gets the symbol ID
            $id                    = $stream->littleEndianUnsignedShort();
            
            // Gets the symbol name
            $name                  = $stream->nullTerminatedString();
            
            // Stores the symbol
            $this->_symbols[ $id ] = $name;
            $this->_symbolIds[]    = $id;
        }
    }
    
    /**
     * Adds a symbol
     * 
     * @param   int     The symbol ID
     * @param   string  The fully-qualified name of the ActionScript 3.0 class with which to associate this symbol
     * @return  void
     */
    public function addSymbol( $id, $name )
    {
        // Ensures we have correct values
        $id   = ( int )$id;
        $name = ( string )$name;
        
        // Checks if a symbol with the same ID exists
        if( isset( $this->_symbols[ $id ] ) ) {
            
            // Updates the symbol
            $this->_symbols[ $id ] = $name;
            
        } else {
            
            // Adds the symbol
            $this->_symbols[ $id ] = $name;
            $this->_symbolIds[]    = $id;
        }
    }
    
    /**
     * Removes a symbol
     * 
     * @param   int     The symbol ID
     * @return  void
     */
    public function removeSymbol( $id )
    {
        // Ensures we have an integer
        $id   = ( int )$id;
        
        // Checks if the symbol ID exists
        if( isset( $this->_symbols[ $id ] ) ) {
            
            // Removes the symbol
            unset( $this->_symbols[ $id ] );
            unset( $this->_symbolIds[ array_search( $id, $this->_symbolIds ) ] );
        }
    }
}
