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
?>
<html>
<head>
	<title>DemoApplication :: <?= $this->getContents('title') ?></title>
</head>
<body>
<h1>Default</h1>
<?= $this->getContents(CONTENT_FOR_LAYOUT) ?>
</body>
</html>