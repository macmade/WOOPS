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
; @type string
; @required
driver      = mysql

; The database host
; @type string
; @required
host        = localhost

; The database port
; @type     int
; @required
port        = 3306

; The database username
; @type string
; @required
user        = woops

; The database user password
; @type string
; @required
password    = woops

; The name of the database to use
; @type string
; @required
name        = woops

; The prefix to use for all WOOPS tables
; @type string
tablePrefix = WOOPS_

; ##############################################################################
; # Date and time settings                                                     #
; ##############################################################################

[time]

; The default timezone
; @type string
; @required
timezone = Europe/Zurich

; ##############################################################################
; # Language settings                                                          #
; ##############################################################################

[lang]

; The default language
; @type string
; @required
defaultLanguage = en

; ##############################################################################
; # XHTML settings                                                             #
; ##############################################################################

[xhtml]

; Format and indent the generated XHTML code
; @type boolean
format = On

; ##############################################################################
; # AOP related settings                                                       #
; ##############################################################################

[aop]

; Prevent the classes matching this pattern to be stored in the class cache
; @type     string
cacheDenyPattern =

; ##############################################################################
; # Error related settings                                                     #
; ##############################################################################

[error]

; The error reporting level
; @type     select
; @option   development
; @option   production
; @option   none
report = development

; ##############################################################################
; # Modules related settings                                                     #
; ##############################################################################

[modules]

; The loaded (active) modules
loaded[] = Cms
loaded[] = Admin
loaded[] = ModManager
loaded[] = HelloWorld
