<?php ob_start(); ?>
<?php 
	//data source name
	$dsn = "mysql:host=localhost;dbname=azweb";

	try {
		$pdo = new PDO($dsn, 'root', '');
		
	} catch (PDOException $e) {
		echo $e->getMessage();
	}

?>