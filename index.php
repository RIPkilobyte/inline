<html>
	<head>
		<title>JSONPARSER</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	</head>
	<body>
<?php
$mysqli = new mysqli('localhost', 'root', '', 'testdb');
?>

<div class="container">
	<div class="col-lg-12">
		<h2 class="mb-3">Поиск</h2>
		<form method="post" id="search_form" autocomplete="off" onsubmit="return submit_form();">
			<div class="row col-12">
				<div class="col-sm-12">
					<label for="search" class="form-label">Введите строку не менее 3-х символов</label>
					<input type="text" class="form-control" name="search" id="search" placeholder="" value="" required>
					<br />
				</div>
			</div>
			<div class="row col-12">
				<div class="col-sm-12">
					<button class="w-100 btn btn-primary btn-lg" type="submit">Найти</button>
				</div>
			</div>
		</form>

<?php if($_POST['search']): ?>
		<h2 class="mb-3">Результаты поиска:</h2>
	<?php
		$search = '%'.$_POST['search'].'%';

		$sql = "SELECT cm.* , p.title
		FROM comments cm
		LEFT JOIN posts p ON p.id = cm.post_id
		WHERE cm.body LIKE ?
		ORDER BY cm.post_id DESC";

		$sqltmp = $mysqli->prepare($sql);
		$sqltmp->bind_param("s", $search);
		$sqltmp->execute();
		$results = $sqltmp->get_result();

		while($res = $results->fetch_assoc()) { 
	?>
		<div class="row col-12">
			<div class="col-sm-12">
				<h3><?=$res['title']?></h3>
				<p>Автор: <?=$res['name']?></p>
				<p>Email: <?=$res['email']?></p>
				<p>Комментарий:</p>
				<p><?=$res['body']?></p>
				<hr />
			</div>
		</div>

	<?php } ?>
<?php endif; ?>
<?php
$mysqli->close();
?>
	</div>
</div>
<script type="text/javascript">
function submit_form() {
	var str = document.getElementById("search").value;
	if(str.length < 3) {
		alert('Вы должны ввести не менее 3- символов для поиска');
		return false;
	}
	return true;
}
</script>
	</body>
</html>