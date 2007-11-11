<?php
/*
+-----------------------------------------------------------------------+
| SkyApp - The PHP Application Framework.                               |
| http://www.skyweb.ro/                                                 |
+-----------------------------------------------------------------------+
| This source file is released under LGPL license, available through    |
| the world wide web at http://www.gnu.org/copyleft/lesser.html.        |
| This library is distributed WITHOUT ANY WARRANTY. Please see the LGPL |
| for more details.                                                     |
+-----------------------------------------------------------------------+
| Authors: Andi Trînculescu <andi@skyweb.ro>                            |
+-----------------------------------------------------------------------+

$Id$
*/

/*! \brief The exception type which will be triggered
 * if the instantiated class does not implement SAIPage interface
 */
class DoesNotImplementSAIPageException extends Exception {}

/*! \brief The exception type which will be triggered
 * if the user modified the request parameters
 */
class URLManipulationException extends Exception {}