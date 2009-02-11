; WOOPS configuration file <?php exit(); ?>

; ##############################################################################
; #                                                                            #
; #               WOOPS - Web Object Oriented Programming System               #
; #                                                                            #
; #                              COPYRIGHT NOTICE                              #
; #                                                                            #
; # (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)             #
; # All rights reserved                                                        #
; ##############################################################################

;  $Id$

; ##############################################################################
; # Database settings                                                          #
; ##############################################################################

[database]

; The database driver to use, with PDO
driver      = mysql

; The database host
host        = localhost

; The database port
port        = 3306

; The database username
user        = woops

; The database user password
password    = woops

; The name of the database to use
name        = woops

; The prefix to use for all WOOPS tables
tablePrefix = WOOPS_

; ##############################################################################
; # Date and time settings                                                     #
; ##############################################################################

[time]

; The default timezone
timezone = Europe/Zurich

; ##############################################################################
; # Language settings                                                          #
; ##############################################################################

[lang]

; The default language
defaultLanguage = en

; ##############################################################################
; # XHTML settings                                                             #
; ##############################################################################

[xhtml]

; Format and indent the generated XHTML code
format               = On

; ##############################################################################
; # Error related settings                                                     #
; ##############################################################################

[error]

; The error report type (development - production - none)
report = development

; ##############################################################################
; # Modules related settings                                                     #
; ##############################################################################

[modules]

; The loaded (active) modules
loaded[] = XhtmlPageEngine
