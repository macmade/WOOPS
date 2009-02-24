; WOOPS configuration file <?php exit(); ?>

; ##############################################################################
; #                                                                            #
; #               WOOPS - Web Object Oriented Programming System               #
; #                                                                            #
; #                              COPYRIGHT NOTICE                              #
; #                                                                            #
; # Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)               #
; # All rights reserved                                                        #
; ##############################################################################

;  $Id$

; ##############################################################################
; # XHTML page engine settings                                                 #
; ##############################################################################

[pageEngine]

; The output charset
; @type string
; @required
charset              = utf-8

; The XHTML document type
; @type     select
; @type     xhtml11
; @option   xhtml1-strict
; @option   xhtml1-transitional
; @option   xhtml1-frameset
; @option   none
doctype              = xhtml1-strict

; Inserts the XML declaration
; @type boolean
insertXmlDeclaration = On

; Inserts the XHTML document type
; @type boolean
insertDoctype        = On
