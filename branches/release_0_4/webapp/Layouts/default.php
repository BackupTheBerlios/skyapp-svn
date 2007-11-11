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
	<style>
		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: .90em;
		}
	</style>
</head>
<body>
<h1>Congratulations, SA is up and running</h1>
<p>
	<strong>Page name</strong>: <?= $this->pageName ?><br>
	<strong>Layout</strong>: <?= $this->getLayoutDir() ?>/<?= $this->getLayout() ?><br>	
	<strong>Page class</strong>: <?= $this->className ?><br>
	<strong>Find me in</strong>: <?= $this->app->getPageSearchDirectory() ?>/<?= $this->getName() ?>.php<br>
<?php
if ($this->getTemplate()):
?>
	<strong>Smarty template</strong>: <?= $this->getTemplateDir() ?>/<?= $this->getTemplate() ?><br>
<?php
endif;
?>
</p>
<?= $this->getContents(CONTENT_FOR_LAYOUT) ?>
</body>
</html>