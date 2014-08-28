<!DOCTYPE html>
<html>
	<head>
		<title>TV Series</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?= __http_path ?>/assets/css/main.css"/>
		<?= html::getStyles() ?>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<?= html::getScripts() ?>
	</head>
	<body>
		<div class="wrapper">
			<header><a title="torna alla home" href="<?= __http_path ?>"></a></header>
			<div class="left">
				<?= $left; ?>
			</div>
			<div class="page">
				<?= $page; ?>
			</div>
		</div>
	</body>
</html>
