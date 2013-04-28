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
 * MPEG-4 STSD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class SampleDescriptionBox( unsigned int( 32 ) handler_type ) extends FullBox( 'stsd', 0, 0 )
 * {
 *      int i ;
 *      unsigned int( 32 ) entry_count;
 *      
 *      for( i = 1; i <= entry_count; i++ ) {
 *          
 *          switch( handler_type ) {
 *              
 *              case 'soun':
 *                  
 *                  AudioSampleEntry();
 *                  break;
 *              
 *              case 'vide':
 *                  
 *                  VisualSampleEntry();
 *                  break;
 *              
 *              case 'hint':
 *                  
 *                  HintSampleEntry();
 *                  break;
 *          }
 *      }
 * }
 * 
 * aligned( 8 ) abstract class SampleEntry( unsigned int( 32 ) format ) extends Box( format )
 * {
 *      const unsigned int( 8 )[ 6 ] reserved = 0;
 *      unsigned int( 16 ) data_reference_index;
 * }
 * 
 * class HintSampleEntry() extends SampleEntry( protocol )
 * {
 *      unsigned int( 8 ) data[];
 * }
 * 
 * class VisualSampleEntry( codingname ) extends SampleEntry( codingname )
 * {
 *      unsigned int( 16 ) pre_defined = 0;
 *      const unsigned int( 16 ) reserved = 0;
 *      unsigned int( 32 )[ 3 ] pre_defined = 0;
 *      unsigned int( 16 ) width;
 *      unsigned int( 16 ) height;
 *      template unsigned int( 32 ) horizresolution = 0x00480000;
 *      template unsigned int( 32 ) vertresolution  = 0x00480000;
 *      const unsigned int( 32 ) reserved = 0;
 *      template unsigned int( 16 ) frame_count = 1;
 *      string[ 32 ] compressorname;
 *      template unsigned int( 16 ) depth = 0x0018;
 *      int( 16 ) pre_defined = -1;
 * }
 * 
 * class AudioSampleEntry( codingname ) extends SampleEntry( codingname )
 * {
 *      const unsigned int( 32 )[ 2 ] reserved = 0;
 *      template unsigned int( 16 ) channelcount = 2;
 *      template unsigned int( 16 ) samplesize = 16;
 *      unsigned int( 16 ) pre_defined = 0;
 *      const unsigned int( 16 ) reserved = 0 ;
 *      template unsigned int( 32 ) samplerate = { timescale of media } << 16;
 * }
 * </code>
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Stsd extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stsd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     * @see     _audioSampleEntry
     * @see     _visualSampleEntry
     * @see     _hintSampleEntries
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        
        // Checks for the HDLR atom
        if( !isset( $this->_parent->_parent->_parent->hdlr ) ) {
            
            // No HDLR atom
            return $data;
        }
        
        // Gets data from HDLR
        $hdlr = $this->_parent->_parent->_parent->hdlr->getProcessedData();
        
        // Storage for the entries            
        $data->entries     = array();
        
        // Process each entry
        for( $i = 0; $i < $data->entry_count; $i++ ) {
            
            // Checks the handler type
            if( $hdlr->handler_type === 'soun' ) {
                
                // Returns the atom processed data (audio entry)
                $data->entries[] = $this->_audioSampleEntry();
                
            } elseif( $hdlr->handler_type === 'vide' ) {
                
                // Returns the atom processed data (visual entry)
                $data->entries[] = $this->_visualSampleEntry();
                
            } elseif( $hdlr->handler_type === 'hint' ) {
                
                $data->entries = $this->_hintSampleEntries();
            }
        }
        
        // Unrecognized handler type
        return $data;
    }
    
    /**
     * Process the atom data (audio entry)
     * 
     * @return  object  The processed atom data
     */
    protected function _audioSampleEntry()
    {
        $data = new stdClass();
        
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->format = $this->_stream->read( 4 );
        
        $this->_stream->seek( 6, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->data_reference_index = $this->_stream->bigEndianUnsignedShort();
        
        $this->_stream->seek( 8, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->channelcount = $this->_stream->bigEndianUnsignedShort();
        $data->samplesize   = $this->_stream->bigEndianUnsignedShort();
        
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->samplerate = $this->_stream->bigEndianFixedPoint( 16, 16 );
        
        // ES descriptor here...
        
        return $data;
    }
    
    /**
     * Process the atom data (visual entry)
     * 
     * @return  object  The processed atom data
     */
    protected function _visualSampleEntry()
    {
        $data = new stdClass();
        
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->codingname = $this->_stream->read( 4 );
        
        $this->_stream->seek( 6, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->data_reference_index = $this->_stream->bigEndianUnsignedShort();
        
        $this->_stream->seek( 16, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->width           = $this->_stream->bigEndianUnsignedShort();
        $data->height          = $this->_stream->bigEndianUnsignedShort();
        $data->horizresolution = $this->_stream->bigEndianFixedPoint( 16, 16 );
        $data->vertresolution  = $this->_stream->bigEndianFixedPoint( 16, 16 );
        
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->frame_count    = $this->_stream->bigEndianUnsignedShort();
        
        $compressorNameLength = $this->_stream->bigEndianUnsignedLong();
        
        if( $compressorNameLength > 0 ) {
            
            $data->compressorname = $this->_stream->read( $compressorNameLength );
            
            $this->_stream->seek( 31 - strlen( $data->compressorname ), Woops_Mpeg4_Binary_Stream::SEEK_CUR );
            
        } else {
            
            $data->compressorname = '';
        }
        
        $data->depth = $this->_stream->bigEndianUnsignedShort();
        
        // ES descriptor here...
        
        return $data;
    }
    
    /**
     * Process the atom data (hint entries)
     * 
     * @return  object  The processed atom data
     */
    protected function _hintSampleEntries()
    {
        $data = new stdClass();
        
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->protocol = $this->_stream->read( 4 );
        
        $this->_stream->seek( 6, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        
        $data->data_reference_index = $this->_stream->bigEndianUnsignedShort();
        
        $data->data = array();
        
        while( !$this->_stream->endOfStream() ) {
            
            $data->data[] = $this->_stream->unsignedChar();
        }
        
        return $data;
    }
}
