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
 * Abstract for the MPEG4 data atoms
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4
 */
abstract class Woops_Mpeg4_DataAtom extends Woops_Mpeg4_Atom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function getProcessedData();
    
    protected $_data       = '';
    protected $_dataLength = 0;
    protected $_final      = false;
    protected $_stream     = NULL;
    
    /**
     * 
     */
    public function __toString()
    {
        // Checks if the atom is final
        if( $this->_final ) {
            
            // Length is set to 0
            $length = pack( 'N', 0 );
            
            // Returns the whole atom
            return $length . $this->_type . $this->_data;
            
        } elseif( $this->_extended ) {
            
            // The atom has an extended length
            $length = $this->_dataLength + 16;
            
            $length32 = $length & 0x00001111;
            $length64 = $length >> 32;
            
            $length = pack( 'N/N', $length64, $length32 );
            
            // Returns the whole atom
            return  pack( 'N', 1 ) . $this->_type . $length . $this->_data;
            
        } else {
            
            // Computes the atom length
            $length = pack( 'N', $this->_dataLength + 8 );
            
            // Returns the whole atom
            return $length . $this->_type . $this->_data;
        }
    }
    
    public function getRawData()
    {
        return $this->_data;
    }
    
    public function getLength()
    {
        if( $this->_final ) {
            
            return 1;
            
        } elseif( $this->_extended ) {
            
            return $this->_dataLength + 16;
            
        } else {
            
            return $this->_dataLength + 8;
        }
    }
    
    public function getDataLength()
    {
        return $this->_dataLength;
    }
    
    public function getHexData( $chunkSplit = 0, $sep = ' ' )
    {
        if( $chunkSplit ) {
            
            return chunk_split( bin2hex( $this->_data ), ( int )$chunkSplit, ( string )$sep );
        }
        
        return bin2hex( $this->_data );
    }
    
    public function getBinData( $chunkSplit = 0, $sep = ' ' )
    {
        $bin = '';
        
        for( $i = 0; $i < $this->_dataLength; $i++  ) {
            
            $bin .= str_pad( decbin( ord( substr( $this->_data, $i, 1 ) ) ), 8, 0, STR_PAD_LEFT );
        }
        
        if( $chunkSplit ) {
            
            return chunk_split( $bin, ( int )$chunkSplit, ( string )$sep );
        }
        
        return $bin;
    }
    
    public function setRawData( $data )
    {
        $this->_data       = $data;
        $this->_dataLength = strlen( $data );
        $this->_stream     = new Woops_Mpeg4_Binary_Stream( $data );
        
        return true;
    }
    
    public function setFinal( $value = true )
    {
        $this->_final = ( boolean )$value;
        
        return true;
    }
    
    public function isFinal()
    {
        return $this->_final;
    }
}
