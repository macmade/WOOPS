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

; The default database engine to use
; 
; @type string
; @required
engine      = pdo

; The database driver to use
; 
; @type string
; @required
driver      = mysql

; The database host
; 
; @type string
; @required
host        = localhost

; The database port
; 
; @type     int
; @required
port        = 3306

; The database username
; 
; @type string
; @required
user        = woops

; The database user password
; 
; @type string
; @required
password    = woops

; The name of the database to use
; 
; @type string
; @required
database    = woops

; The prefix to use for all WOOPS tables
; 
; @type string
tablePrefix = WOOPS_

; ##############################################################################
; # Date and time settings                                                     #
; ##############################################################################

[time]

; The default timezone
; 
; @type string
; @required
timezone = Europe/Zurich

; ##############################################################################
; # Language settings                                                          #
; ##############################################################################

[lang]

; The default language
; 
; @type string
; @required
defaultLanguage = en

; ##############################################################################
; # XHTML settings                                                             #
; ##############################################################################

[xhtml]

; Format and indent the generated XHTML code
; 
; @type boolean
format = On

; ##############################################################################
; # Class cache related settings                                               #
; ##############################################################################

[classCache]

; Allows WOOPS classes to be stored in the class cache directory.
; 
; @type boolean
enable   = On

; Optimizes the PHP source code before putting classes in the cache.
; 
; @type boolean
optimize = On

; ##############################################################################
; # AOP related settings                                                       #
; ##############################################################################

[aop]

; Allows the generation of AOP classes. Only turn off on production boxes!
; 
; Note that if this settings is on, the class cache option will automatically
; be enabled.
; 
; @type boolean
enable = On

; ##############################################################################
; # Error related settings                                                     #
; ##############################################################################

[error]

; The error reporting level
; 
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
loaded[] = Pdo
loaded[] = Cms
loaded[] = Admin
loaded[] = ModManager
loaded[] = HelloWorld
loaded[] = AopTest
