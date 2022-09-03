<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		require_once('inc/search.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Поиск</title>
</head>
<body>
	<form method="POST">
		<input type="text" name="q" value="<?= !empty($_POST['q']) ? $_POST['q'] : ''; ?>" placeholder="Поиск">
		<button>Найти</button>
	</form>
	<?php 
		if(!empty($message)) :
	?>
		<div>
			<p><?= $message; ?></p>
		</div>
	<?php 
		endif;
	?>

	<?php 
		if(!empty($posts)) :
			foreach ($posts as $post) :
	?>
				<div>
					<p><b><?= $post['title']; ?></b></p>
					<?php
						foreach ($post['comments'] as $comment) :
					?>

						<p>
							Name: <?= $comment['name']; ?></br>
							Email: <?= $comment['email']; ?></br>
							Body: <?= $comment['body']; ?></br>
						</p>

					<?php 
						endforeach;
					?>
				</div>
				<hr>
	
	<?php 
			endforeach;
		endif;
	?>
</body>
</html>
