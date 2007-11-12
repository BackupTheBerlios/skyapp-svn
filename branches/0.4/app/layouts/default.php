<html>
<head>
	<base href="<?= SA_Url::baseHref() ?>">
	<style>
		body {
			margin-top: 5px;
			margin-left: 5px;
		}
	</style>
</head>
<body>
<h1>Congratulations, SA is up and running!</h1>
<div><b>Page name</b>: <?= $this->getName() ?></div>
<div><b>Layout</b>: <?= $this->app->getApplicationDirectory() . 'layouts/' . $this->getLayout() ?></div>
<div><b>Page class</b>: <?= get_class($this) ?></div>
<div><b>Find me in</b>: <?= $this->app->getApplicationDirectory() . 'pages/' . $this->getName() . '.php' ?></div>
<div><b>Smarty template</b>: <?= $this->app->getApplicationDirectory() . 'templates/' . $this->getTemplate() ?></div>
<p><?= $this->getContents(CONTENT_FOR_LAYOUT) ?></p>
</body>
</html>