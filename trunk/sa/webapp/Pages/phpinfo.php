<?php
/*
+-----------------------------------------------------------------------+
| SkyApp, The PHP Application Framework.                                |
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

class Page_phpinfo extends DemoPage {	
	function display() {
		ob_start();
		phpinfo();
		$contents = ob_get_contents();
		ob_end_clean();		
		$this->setContents(CONTENT_FOR_LAYOUT, $contents);
		$this->setTemplate(null);		
		parent::display();
	}
}