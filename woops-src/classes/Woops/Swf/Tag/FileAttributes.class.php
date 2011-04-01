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
 * SWF FileAttributes tag
 * 
 * The FileAttributes tag defines characteristics of the SWF file. This tag is
 * required for SWF 8 and later and must be the first tag in the SWF file.
 * Additionally, the FileAttributes tag can optionally be included in all SWF
 * file versions.
 * The HasMetadata flag identifies whether the SWF file contains the Metadata
 * tag. Flash Player does not care about this bit field or the related tag but
 * it is useful for search engines.
 * The UseNetwork flag signifies whether Flash Player should grant the SWF file
 * local or network file access if the SWF file is loaded locally. The default
 * behavior is to allow local SWF files to interact with local files only, and
 * not with the network. However, by setting the UseNetwork flag, the local SWF
 * can forfeit its local file system access in exchange for access to the
 * network. Any version of SWF can use the UseNetwork flag to set the file
 * access for locally loaded SWF files that are running in Flash Player 8 or
 * later. 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class Woops_Swf_Tag_FileAttributes extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type             = 0x45;
    
    /**
     * Whether the SWF file uses hardware acceleration to blit graphics to the screen
     */
    protected $_useDirectBlit    = false;
    
    /**
     * Whether the SWF file uses GPU compositing features when drawing graphics
     */
    protected $_useGpu           = false;
    
    /**
     * Whether the SWF file contains the Metadata tag
     */
    protected $_hasMetadata      = false;
    
    /**
     * Whether the SWF file uses ActionScript 3.0
     */
    protected $_useActionScript3 = false;
    
    /**
     * Whether the SWF file is given network file access when loaded locally
     */
    protected $_useNetwork       = false;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Gets the raw data
        $data = $stream->littleEndianUnsignedLong();
        
        // Sets the flags
        $this->_useDirectBlit    = ( boolean )( $data & 0x02 );
        $this->_useGpu           = ( boolean )( $data & 0x04 );
        $this->_hasMetadata      = ( boolean )( $data & 0x08 );
        $this->_useActionScript3 = ( boolean )( $data & 0x10 );
        $this->_useNetwork       = ( boolean )( $data & 0x80 );
    }
    
    /**
     * Checks whether the SWF file uses hardware acceleration to blit graphics to the screen
     * 
     * @return  boolean True if the SWF file uses hardware acceleration to blit graphics to the screen, otherwise false
     */
    public function useDirectBlit()
    {
        return $this->_useDirectBlit;
    }
    
    /**
     * Checks whether the SWF file  uses GPU compositing features when drawing graphics
     * 
     * @return  boolean True if the SWF file  uses GPU compositing features when drawing graphics, otherwise false
     */
    public function useGpu()
    {
        return $this->_useGpu;
    }
    
    /**
     * Checks whether the SWF file contains the Metadata tag
     * 
     * @return  boolean True if the SWF file contains the Metadata tag, otherwise false
     */
    public function hasMetaData()
    {
        return $this->_hasMetadata;
    }
    
    /**
     * Checks whether the SWF file uses ActionScript 3.0
     * 
     * @return  boolean True if the SWF file uses ActionScript 3.0, otherwise false
     */
    public function useActionScript3()
    {
        return $this->_useActionScript3;
    }
    
    /**
     * Checks whether the SWF file is given network file access when loaded locally
     * 
     * @return  boolean True if the SWF file is given network file access when loaded locally, otherwise false
     */
    public function useNetwork()
    {
        return $this->_useNetwork;
    }
    
    /**
     * Decides whether the SWF file uses hardware acceleration to blit graphics to the screen
     * 
     * @param   boolean True if the SWF file uses hardware acceleration to blit graphics to the screen, otherwise false
     * @return  void
     */
    public function setUseDirectBlit( $value )
    {
        $this->_useDirectBlit = ( boolean )$value;
    }
    
    /**
     * Decides whether the SWF file  uses GPU compositing features when drawing graphics
     * 
     * @param   boolean True if the SWF file  uses GPU compositing features when drawing graphics, otherwise false
     * @return  void
     */
    public function setUseGpu( $value )
    {
        $this->_useGpu = ( boolean )$value;
    }
    
    /**
     * Decides whether the SWF file contains the Metadata tag
     * 
     * @param   boolean True if the SWF file contains the Metadata tag, otherwise false
     * @return  void
     */
    public function setHasMetaData( $value )
    {
        $this->_hasMetadata = ( boolean )$value;
    }
    
    /**
     * Decides whether the SWF file uses ActionScript 3.0
     * 
     * @param   boolean True if the SWF file uses ActionScript 3.0, otherwise false
     * @return  void
     */
    public function setUseActionScript3( $value )
    {
        $this->_useActionScript3 = ( boolean )$value;
    }
    
    /**
     * Decides whether the SWF file is given network file access when loaded locally
     * 
     * @param   boolean True if the SWF file is given network file access when loaded locally, otherwise false
     * @return  void
     */
    public function setUseNetwork( $value )
    {
        $this->_useNetwork = ( boolean )$value;
    }
}
