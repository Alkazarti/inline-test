<?php

$count_posts = 0;
$count_comments = 0;

$link_db = @new mysqli('127.0.0.1', 'root', '', 'test');

if ($link_db->connect_error) {
    echo 'Ошибка при подключения к базе данных: ' . $link_db->connect_error . "\n";
    die();
}

$posts = @file_get_contents('https://jsonplaceholder.typicode.com/posts');

if($posts === false) {
	echo "Не удалось прочитать данные posts\n";
	die();
}

$posts = json_decode($posts, false);

if(is_array($posts)){

	foreach ($posts as $post) {

		$sql = "SELECT id FROM posts WHERE id = " . (int)$post->id;
		$result = $link_db->query($sql);

		if($result->num_rows){
			echo "Запись с id = " . $post->id . " уже существует\n";
			continue;
		}

		$post->title = clear_string($post->title, $link_db);
		$post->body = clear_string($post->body, $link_db);

		$sql = "INSERT INTO posts (
								id, 
								userId, 
								title, 
								body
							) VALUES (
								" . (int)$post->id . ",
								" . (int)$post->userId . ",
								'" . $post->title . "',
								'" . $post->body . "'
							)";

		$result = $link_db->query($sql);

		if(!$result){
			echo 'Ошибка добавления записи: ' . $link_db->error . "\n";
		} else {
			$count_posts++;
		}

	}

} else {

	echo "Не удалось извлечь данные posts\n";
	die();

}

$comments = @file_get_contents('https://jsonplaceholder.typicode.com/comments');

if($comments === false) {
	echo "Не удалось прочитать данные comments\n";
	die();
}

$comments = json_decode($comments, false);

if(is_array($comments)){

	foreach ($comments as $comment) {

		$sql = "SELECT id FROM comments WHERE id = " . (int)$comment->id;
		$result = $link_db->query($sql);

		if($result->num_rows){
			echo "Комментарий с id = " . $comment->id . " уже существует\n";
			continue;
		}

		$sql = "SELECT id FROM posts WHERE id = " . (int)$comment->postId;
		$result = $link_db->query($sql);

		if(!$result->num_rows){
			echo "Пост с id = " . $comment->postId . " для комментария с id = " . $comment->id . " не существует\n";
			continue;
		}

		$comment->name = clear_string($comment->name, $link_db);
		$comment->email = clear_string($comment->email, $link_db);
		$comment->body = clear_string($comment->body, $link_db);

		$sql = "INSERT INTO comments (
								id,
								postId,
								name,
								email,
								body
							) VALUES (
								" . (int)$comment->id . ",
								" . (int)$comment->postId . ",
								'" . $comment->name . "',
								'" . $comment->email . "',
								'" . $comment->body . "'
							)";

		$result = $link_db->query($sql);

		if(!$result){
			echo 'Ошибка добавления комментария: ' . $link_db->error . "\n";
		} else {
			$count_comments++;
		}

	}

} else {

	echo "Не удалось извлечь данные comments\n";
	die();

}

echo "Загружено {$count_posts} записей и {$count_comments} комментариев\n";

function clear_string($string, $link_ind){

	return $link_ind->real_escape_string(trim(strip_tags($string)));

}

?>
