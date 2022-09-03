<?php

	if(!$_POST['q']){
		$message = 'Ошибка - отсутствует запрос';
	} elseif(iconv_strlen($_POST['q']) < 3){
		$message = 'Ошибка - длина запроса должна быть не менее 3 символов';
	} else {

		$link_db = @new mysqli('127.0.0.1', 'root', '', 'test');

		if ($link_db->connect_error){
		    $message = 'Ошибка при подключении к базе данных: ' . $link_db->connect_error;
		} else {

			$q = $link_db->real_escape_string($_POST['q']);
			$sql = "SELECT c.name, c.email, c.body, c.postId as post_id, p.title FROM comments as c JOIN posts as p ON p.id = c.postId WHERE c.body LIKE '%" . $q . "%' ";
			$result = $link_db->query($sql);

			if(!$result){
				$message = "Ошибка поиска";
			} elseif(!$result->num_rows){
				$message = "Результаты не найдены";
			} else {
        
				$comments = $result->fetch_all(MYSQLI_ASSOC);
				$posts = [];
				foreach ($comments as $comment) {

					if(empty($posts[$comment['post_id']]['title'])){
						$posts[$comment['post_id']]['title'] = $comment['title'];
					}

					$posts[$comment['post_id']]['comments'][] = array(
																	'name' => $comment['name'],
																	'email' => $comment['email'],
																	'body' => $comment['body']
																);
				}

			}

		}

	}

?>
