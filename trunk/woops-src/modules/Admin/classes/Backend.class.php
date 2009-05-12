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

# $Id: SayHello.class.php 190 2009-02-11 07:55:49Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Mod\Admin;

/**
 * Hello world XHTML block
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Admin
 */
class Backend extends \Woops\Mod\Cms\Block
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    protected function _loginBox( \Woops\Xhtml\Tag $content )
    {
        $this->_includeWebtoolkitScript( 'sha1' );
        
        $content->h1  = $this->_lang->loginBoxTitle;
        $formDiv        = $content->div;
        $form           = $formDiv->form;
        $version        = $content->div;
        
        $version->addTextData(
            sprintf(
                $this->_lang->loginVersion,
                self::WOOPS_VERSION . '-' . self::WOOPS_VERSION_SUFFIX
            )
        );
        
        $userDiv        = $form->div;
        $passDiv        = $form->div;
        $submitDiv      = $form->div;
        
        $userLabelDiv   = $userDiv->div;
        $userInputDiv   = $userDiv->div;
        
        $passLabelDiv   = $passDiv->div;
        $passInputDiv   = $passDiv->div;
        
        $submitLabelDiv = $submitDiv->div;
        $submitInputDiv = $submitDiv->div;
        
        $userLabel      = $userLabelDiv->label;
        $passLabel      = $passLabelDiv->label;
        
        $userInput      = $userInputDiv->input;
        $passInput      = $passInputDiv->input;
        $submitInput    = $submitInputDiv->input;
        
        $userLabel->addTextData( $this->_lang->loginBoxUsername );
        $passLabel->addTextData( $this->_lang->loginBoxPassword );
        
        $userInput[ 'type' ]    = 'text';
        $userInput[ 'size' ]    = '20';
        
        $passInput[ 'type' ]    = 'password';
        $passInput[ 'size' ]    = '20';
        
        $submitInput[ 'type' ]  = 'submit';
        $submitInput[ 'value' ] = $this->_lang->loginBoxSubmit;
        
        $this->_cssClass( $content, 'Login' );
        $this->_cssClass( $formDiv,   'Login-Form' );
        
        $this->_cssClass( $userDiv,   'Login-User' );
        $this->_cssClass( $passDiv,   'Login-Pass' );
        $this->_cssClass( $submitDiv, 'Login-Submit' );
        
        $this->_cssClass( $userLabelDiv,   'Login-Label' );
        $this->_cssClass( $userInputDiv,   'Login-Input' );
        $this->_cssClass( $passLabelDiv,   'Login-Label' );
        $this->_cssClass( $passInputDiv,   'Login-Input' );
        $this->_cssClass( $submitLabelDiv, 'Login-Label' );
        $this->_cssClass( $submitInputDiv, 'Login-Input' );
        $this->_cssClass( $version, 'Login-Version' );
    }
    
    /**
     * 
     */
    public function getBlockContent( \Woops\Xhtml\Tag $content, \stdClass $options )
    {
        $this->_includeJQuery();
        $container = $content->div;
        $this->_loginBox( $container );
    }
}
