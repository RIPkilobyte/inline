<?php
$mysqli = new mysqli('localhost', 'root', '', 'testdb');

$posts = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/posts'), 1);
$inserted_posts = 0;
if($posts) {
	foreach($posts as $post) {
		$sqltmp = $mysqli->prepare("SELECT id FROM posts WHERE id = ?");
		$sqltmp->bind_param("i", $post['id']);
		$sqltmp->execute();
		$result = $sqltmp->get_result();
		if($result->num_rows == 0) {
			$sqltmp = $mysqli->prepare("INSERT INTO posts (id, user_id, title, body) VALUES (?, ?, ?, ?)");
			$sqltmp->bind_param("iiss", $post['id'], $post['userId'], $post['title'], $post['body']);
			$sqltmp->execute();
			$inserted_posts++;
		}
	}
}

$comments = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/comments'), 1);
$inserted_comments = 0;
if($comments) {
	foreach($comments as $comment) {
		$sqltmp = $mysqli->prepare("SELECT id FROM comments WHERE id = ?");
		$sqltmp->bind_param("i", $comment['id']);
		$sqltmp->execute();
		$result = $sqltmp->get_result();
		if($result->num_rows == 0) {
			$sqltmp = $mysqli->prepare("INSERT INTO comments (id, post_id, name, email, body) VALUES (?, ?, ?, ?, ?)");
			$sqltmp->bind_param("iisss", $comment['id'], $comment['postId'], $comment['name'], $comment['email'], $comment['body']);
			$sqltmp->execute();
			$inserted_comments++;
		}
	}
}

echo "Загружено ".$inserted_posts." записей и ".$inserted_comments." комментариев\n";
$mysqli->close();