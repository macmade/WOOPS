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
 * SWF Metadata tag
 * 
 * The Metadata tag is an optional tag to describe the SWF file to an external
 * process. The tag embeds XML metadata in the SWF file so that, for example, a
 * search engine can locate this tag, access a title for the SWF file, and
 * display that title in search results. Flash Player always ignores the
 * Metadata tag.
 * If the Metadata tag is included in a SWF file, the FileAttributes tag must
 * also be in the SWF file with its HasMetadata flag set. Conversely, if the
 * FileAttributes tag has the HasMetadata flag set, the Metadata tag must be in
 * the SWF file. The Metadata tag can only be in the SWF file one time.
 * The format of the metadata is RDF that is compliant with Adobe’s Extensible
 * Metadata Platform (XMP™) specification. For more information about RDF and
 * XMP, see the following sources:
 *      -# The RDF Primer at www.w3.org/TR/rdf-primer
 *      -# The RDF Specification at www.w3.org/TR/1999/REC-rdf-syntax-19990222
 *      -# The XMP home page at www.adobe.com/products/xmp
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class Woops_Swf_Tag_Metadata extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x4D;
    
    /**
     * The XML data
     */
    protected $_xml  = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $this->_xml = $stream->nullTerminatedString();
    }
    
    /**
     * Gets the XML data
     * 
     * @return  string  The XML data
     */
    public function getXml()
    {
        return $this->_xml;
    }
    
    /**
     * Sets the XML data
     * 
     * @param   string  The XML data
     * @return  void
     */
    public function setXml( $data )
    {
        $this->_xml = ( string )$data;
    }
}
