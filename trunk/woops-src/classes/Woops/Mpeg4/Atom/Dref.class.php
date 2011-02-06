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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Mpeg4\Atom;

/**
 * MPEG-4 DREF atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Dref extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'dref';
    
    protected function _processFlags( $flags )
    {
        return new \stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        $data->entries     = array();
        
        while( !$this->_stream->endOfStream() )
        {
            $entryLength = $this->_stream->bigEndianUnsignedLong();
            $entryType   = $this->_stream->read( 4 );
            
            if( $entryType === 'urn ' )
            {
                $entry = $this->_dataEntryUrnBox( $this->_stream->read( $entryLength - 8 ) );
            }
            elseif( $entryType === 'url ' )
            {
                $entry = $this->_dataEntryUrlBox( $this->_stream->read( $entryLength - 8 ) );
            }
            else
            {
                $entry = new \stdClass();
            }
            
            $data->entries[] = $entry;
        }
        
        return $data;
    }
    
    protected function _dataEntryUrnBox( $rawData )
    {
        $data       = $this->_dataEntryBox( $rawData );
        $data->type = 'urn ';
        
        if( strlen( $rawData ) > 4 )
        {
            $sep            = strpos( $rawData, chr( 0 ) );
            $data->name     = substr( $rawData, 4, $sep - 4 );
            $data->location = substr( $rawData, $sep + 1, -1 );
        }
        
        return $data;
    }
    
    protected function _dataEntryUrlBox( $rawData )
    {
        $data       = $this->_dataEntryBox( $rawData );
        $data->type = 'url ';
        
        if( strlen( $rawData ) > 4 )
        {
            $data->location = substr( $rawData, 4 );
        }
        
        return $data;
    }
    
    protected function _dataEntryBox( $rawData )
    {
        $data                        = new \stdClass();
        $version                     = unpack( 'N', str_pad( substr( $rawData, 0, 1 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        $flags                       = unpack( 'N', str_pad( substr( $rawData, 1, 3 ), 4, chr( 0 ), STR_PAD_LEFT ) );
        $data->version               = $version[ 1 ];
        $data->flags                 = new \stdClass();
        $data->flags->self_reference = $flags & 0x000001;
        
        return $data;
    }
}
