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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * ZIP local file header
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Zip.Local.File
 */
class Woops_Zip_Local_File_Header extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The supported host systems
     */
    const OS_MSDOS                                                    = 0x00;
    const OS_OS2                                                      = 0x00;
    const OS_FAT                                                      = 0x00;
    const OS_FAT32                                                    = 0x00;
    const OS_AMIGA                                                    = 0x01;
    const OS_OPEN_VMS                                                 = 0x02;
    const OS_UNIX                                                     = 0x03;
    const OS_VMCMS                                                    = 0x04;
    const OS_ATARI_ST                                                 = 0x05;
    const OS_OS2_HPFS                                                 = 0x06;
    const OS_MACINTOSH                                                = 0x07;
    const OS_Z_SYSTEM                                                 = 0x08;
    const OS_CPM                                                      = 0x09;
    const OS_WINDOWS_NTFS                                             = 0x0A;
    const OS_MVS                                                      = 0x0B;
    const OS_OS390                                                    = 0x0B;
    const OS_ZOS                                                      = 0x0B;
    const OS_VSE                                                      = 0x0C;
    const OS_ACORN_RISC                                               = 0x0D;
    const OS_VFAT                                                     = 0x0E;
    const OS_ALTERNATE_MVS                                            = 0x0F;
    const OS_BEOS                                                     = 0x10;
    const OS_TANDEM                                                   = 0x11;
    const OS_OS400                                                    = 0x12;
    const OS_OSX                                                      = 0x13;
    const OS_DARWIN                                                   = 0x13;
    
    /**
     * 
     */
    const VERSION_DEFAULT                                             = 0x0;
    const VERSION_VOLUME                                              = 0x0;
    const VERSION_DIRECTORY                                           = 0x0;
    const VERSION_DEFLATE                                             = 0x0;
    const VERSION_PKWARE                                              = 0x0;
    const VERSION_DEFLATE_64                                          = 0x0;
    const VERSION_PKWARE_DCL_IMPLODE                                  = 0x0;
    const VERSION_PATCH_DATA_SET                                      = 0x0;
    const VERSION_ZIP_64                                              = 0x0;
    const VERSION_BZIP2                                               = 0x0;
    const VERSION_DES                                                 = 0x0;
    const VERSION_3DES                                                = 0x0;
    const VERSION_RC2                                                 = 0x0;
    const VERSION_RC4                                                 = 0x0;
    const VERSION_AES                                                 = 0x0;
    const VERSION_CORRECTED_RC2                                       = 0x0;
    const VERSION_CORRECTED_RC2_64                                    = 0x0;
    const VERSION_NON_OAP_KEY_WRAPPING                                = 0x0;
    const VERSION_CENTRAL_DIRECTORY_ENCRYPTION                        = 0x0;
    const VERSION_LZMA                                                = 0x0;
    const VERSION_PPMD_PLUS                                           = 0x0;
    const VERSION_BLOWFISH                                            = 0x0;
    const VERSION_TWOFISH                                             = 0x0;
    
    /**
     * 
     */
    const COMPRESSION_STORED                                          = 0x0;
    const COMPRESSION_SHRUNK                                          = 0x0;
    const COMPRESSION_REDUCED_FACTOR_1                                = 0x0;
    const COMPRESSION_REDUCED_FACTOR_2                                = 0x0;
    const COMPRESSION_REDUCED_FACTOR_3                                = 0x0;
    const COMPRESSION_REDUCED_FACTOR_4                                = 0x0;
    const COMPRESSION_IMPLODED                                        = 0x0;
    const COMPRESSION_TOKENIZING                                      = 0x0;
    const COMPRESSION_DEFLATE                                         = 0x0;
    const COMPRESSION_DEFLATE_64                                      = 0x0;
    const COMPRESSION_PKWARE_DCL_IMPLODE                              = 0x0;
    const COMPRESSION_BZIP2                                           = 0x0;
    const COMPRESSION_LZMA                                            = 0x0;
    const COMPRESSION_IBM_TERSE                                       = 0x0;
    const COMPRESSION_IBM_LZ77                                        = 0x0;
    const COMPRESSION_WAV_PACK                                        = 0x0;
    const COMPRESSION_PPMD                                            = 0x0;
    
    /**
     * Extra fields from PKWARE
     */
    const EXTRA_ZIP64_EXTENDED_INFORMATION                            = 0x0001;
    const EXTRA_AV_INFO                                               = 0x0007;
    const EXTRA_EXTENDED_LANGUAGE_ENCODING_DATA                       = 0x0008;
    const EXTRA_OS2                                                   = 0x0009;
    const EXTRA_NTFS                                                  = 0x000A;
    const EXTRA_OPEN_VMS                                              = 0x000C;
    const EXTRA_UNIX                                                  = 0x000D;
    const EXTRA_FILE_STREAM_AND_FORK_DESCRIPTORS                      = 0x000E;
    const EXTRA_PATCH_DESCRIPTOR                                      = 0x000F;
    const EXTRA_PKCS7_STORE_FOR_X509_CERTIFICATES                     = 0x0014;
    const EXTRA_X509_CERTIFICATE_ID_AND_SIGNATURE_FOR_INDIVIDUAL_FILE = 0x0015;
    const EXTRA_X509_CERTIFICATE_ID_FOR_CENTRAL_DIRECTORY             = 0x0016;
    const EXTRA_STRONG_ENCRYPTION_HEADER                              = 0x0017;
    const EXTRA_RECORD_MANAGEMENT_CONTROLS                            = 0x0018;
    const EXTRA_PKCS7_ENCRYPTION_RECIPIENT_CERTIFICATE_LIST           = 0x0019;
    const EXTRA_IBM_S390_ATTRIBUTES_UNCOMPRESSED                      = 0x0065;
    const EXTRA_IBM_S390_ATTRIBUTES_COMPRESSED                        = 0x0066;
    const EXTRA_POS_ZIP4690                                           = 0x4690;
    
    /**
     * Extra fields third party mappings commonly used
     */
    const EXTRA_MACINTOSH                                             = 0x07C8;
    const EXTRA_ZIP_IT_MACINTOSH                                      = 0x2605;
    const EXTRA_ZIP_IT_MACINTOSH_135                                  = 0x2705;
    const EXTRA_ZIP_IT_MACINTOSH_135_ALT                              = 0x2805;
    const EXTRA_INFO_ZIP_MACINTOSH                                    = 0x334D;
    const EXTRA_ACORN_SPARK_FS                                        = 0x4341;
    const EXTRA_WINDOWS_NT_SECURITY_DESCRIPTOR                        = 0x4453;
    const EXTRA_VM_CMS                                                = 0x4704;
    const EXTRA_MVS                                                   = 0x470F;
    const EXTRA_FWKCS_MD5                                             = 0x4B46;
    const EXTRA_OS2_ACCESS_CONTROL_LIST                               = 0x4C41;
    const EXTRA_INFO_ZIP_OPEN_VMS                                     = 0x4D49;
    const EXTRA_XCEED_ORIGINAL_LOCATION                               = 0x4F4C;
    const EXTRA_AOS_VS                                                = 0x5356;
    const EXTRA_EXTENDED_TIMESTAMP                                    = 0x5455;
    const EXTRA_XCEED_UNICODE                                         = 0x554E;
    const EXTRA_INFO_ZIP_UNIX                                         = 0x5855;
    const EXTRA_INFO_ZIP_UNICODE_COMMENT                              = 0x6375;
    const EXTRA_BE_OS_BE_BOX                                          = 0x6542;
    const EXTRA_INFO_ZIP_UNICODE_PATH                                 = 0x7075;
    const EXTRA_ASI_UNIX                                              = 0x756E;
    const EXTRA_INFO_ZIP_UNIX_NEW                                     = 0x7855;
    const EXTRA_MICROSOFT_OPEN_PACKAGING_GROWTH_HINT                  = 0xA220;
    const EXTRA_SMS_QDOS                                              = 0xFD4A;
    
    /**
     * The types of the extra fields, with their corresponding PHP class
     */
    protected static $_extraTypes = array(
        
        // From PKWARE
        0x0001 => 'Woops_Zip_ExtraField_Zip64_ExtendedInformation',
        0x0007 => 'Woops_Zip_ExtraField_AvInfo',
        0x0008 => 'Woops_Zip_ExtraField_Extended_LanguageEncodingData',
        0x0009 => 'Woops_Zip_ExtraField_Os2',
        0x000A => 'Woops_Zip_ExtraField_Ntfs',
        0x000C => 'Woops_Zip_ExtraField_OpenVms',
        0x000D => 'Woops_Zip_ExtraField_Unix',
        0x000E => 'Woops_Zip_ExtraField_FileStreamAndForkDescriptors',
        0x000F => 'Woops_Zip_ExtraField_PatchDescriptor',
        0x0014 => 'Woops_Zip_ExtraField_Pkcs7_StoreForX509Certificates',
        0x0015 => 'Woops_Zip_ExtraField_X509_Certificate_IdAndSignatureForIndividualFile',
        0x0016 => 'Woops_Zip_ExtraField_X509_Certificate_IdForCentralDirectory',
        0x0017 => 'Woops_Zip_ExtraField_StrongEncryptionHeader',
        0x0018 => 'Woops_Zip_ExtraField_RecordManagementControls',
        0x0019 => 'Woops_Zip_ExtraField_Pkcs7_EncryptionRecipientCertificateList',
        0x0065 => 'Woops_Zip_ExtraField_IbmS390_Attributes_Uncompressed',
        0x0066 => 'Woops_Zip_ExtraField_IbmS390_Attributes_Compressed',
        0x4690 => 'Woops_Zip_ExtraField_PosZip4690',
        
        // Third party mappings commonly used
        0x07C8 => 'Woops_Zip_ExtraField_Macintosh',
        0x2605 => 'Woops_Zip_ExtraField_ZipIt_Macintosh',
        0x2705 => 'Woops_Zip_ExtraField_ZipIt_Macintosh_135',
        0x2805 => 'Woops_Zip_ExtraField_ZipIt_Macintosh_135_Alt',
        0x334D => 'Woops_Zip_ExtraField_InfoZip_Macintosh',
        0x4341 => 'Woops_Zip_ExtraField_AcornSparkFs',
        0x4453 => 'Woops_Zip_ExtraField_Windows_Nt_SecurityDescriptor',
        0x4704 => 'Woops_Zip_ExtraField_VmCms',
        0x470F => 'Woops_Zip_ExtraField_Mvs',
        0x4B46 => 'Woops_Zip_ExtraField_FwkcsMd5',
        0x4C41 => 'Woops_Zip_ExtraField_Os2_AccessControlList',
        0x4D49 => 'Woops_Zip_ExtraField_InfoZip_OpenVms',
        0x4F4C => 'Woops_Zip_ExtraField_Xceed_OriginalLocation',
        0x5356 => 'Woops_Zip_ExtraField_AosVs',
        0x5455 => 'Woops_Zip_ExtraField_Extended_Timestamp',
        0x554E => 'Woops_Zip_ExtraField_Xceed_Unicode',
        0x5855 => 'Woops_Zip_ExtraField_InfoZip_Unix',
        0x6375 => 'Woops_Zip_ExtraField_InfoZip_Unicode_Comment',
        0x6542 => 'Woops_Zip_ExtraField_BeOsBeBox',
        0x7075 => 'Woops_Zip_ExtraField_InfoZip_Unicode_Path',
        0x756E => 'Woops_Zip_ExtraField_AsiUnix',
        0x7855 => 'Woops_Zip_ExtraField_InfoZip_Unix_New',
        0xA220 => 'Woops_Zip_ExtraField_Microsoft_OpenPackagingGrowthHint',
        0xFD4A => 'Woops_Zip_ExtraField_SmsQdos'
    );
    
    /**
     * 
     */
    protected $_extractVersion    = 0;
    
    /**
     * 
     */
    protected $_flags             = 0;
    
    /**
     * 
     */
    protected $_compressionMethod = 0;
    
    /**
     * 
     */
    protected $_mTime             = 0;
    
    /**
     * 
     */
    protected $_mDate             = 0;
    
    /**
     * 
     */
    protected $_crc32             = 0;
    
    /**
     * 
     */
    protected $_compressedSize    = 0;
    
    /**
     * 
     */
    protected $_uncompressedSize  = 0;
    
    /**
     * 
     */
    protected $_extraFields       = array();
    
    /**
     * 
     */
    protected $_fileName          = '';
    
    /**
     * 
     */
    protected function _processExtraField( Woops_Zip_Binary_Stream $stream, $length )
    {
        $this->_extraFields = array();
        
        $read               = $stream->getOffset();
        $end                = $read + $length;
        
        while( $read < $end ) {
            
            $id         = $stream->littleEndianUnsignedShort();
            
            if( isset( self::$_extraTypes[ $id ] ) ) {
                
                $extraFieldClass = self::$_extraTypes[ $id ];
                $extraField      = new $extraFieldClass();
                
            } else {
                
                $extraField = new Woops_Zip_UnknownExtraField( $id );
            }
            
            $this->_extraFields[] = $extraField;
            
            $extraField->processData( $stream );
            
            $read = $stream->getOffset();
        }
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Zip_Binary_Stream The binary stream
     * @return  void
     */
    public function processData( Woops_Zip_Binary_Stream $stream )
    {
        $this->_extractVersion         = $stream->littleEndianUnsignedShort();
        
        $this->_flags                  = $stream->littleEndianUnsignedShort();
        
        $this->_compressionMethod      = $stream->littleEndianUnsignedShort();
        
        $this->_mTime                  = $stream->littleEndianUnsignedShort();
        $this->_mDate                  = $stream->littleEndianUnsignedShort();
        
        $this->_crc32                  = $stream->littleEndianUnsignedLong();
        
        $this->_compressedSize         = $stream->littleEndianUnsignedLong();
        $this->_uncompressedSize       = $stream->littleEndianUnsignedLong();
        
        $fileNameLength                = $stream->littleEndianUnsignedShort();
        $extraFieldLength              = $stream->littleEndianUnsignedShort();
        
        $this->_fileName               = $stream->read( $fileNameLength );
        
        $this->_processExtraField( $stream, $extraFieldLength );
    }
    
    /**
     * Gets the compressed data size
     * 
     * @return  int     The compressed data size
     */
    public function getCompressedSize()
    {
        return $this->_compressedSize;
    }
    
    /**
     * Gets the uncompressed data size
     * 
     * @return  int     The uncompressed data size
     */
    public function getUncompressedSize()
    {
        return $this->_uncompressedSize;
    }
    
    /**
     * Checks if a data descriptor is present
     * 
     * @return  boolean True if a data descriptor is present
     */
    public function hasDataDescriptor()
    {
        return $this->_flags & 0x08;
    }
}
