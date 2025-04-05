<?php
//$mysqli = mysqli_connect("localhost", "testuser", "somepass", "testDB");
//
//if (mysqli_connect_errno()) {
//    printf("Connect failed: %s\n", mysqli_connect_error());
//    exit();
//} else {
//    $clean_text = mysqli_real_escape_string($mysqli, $_POST['testfield']);
//    $sql = "INSERT INTO testTable (testField) VALUES ('" . $clean_text . "')";
//    $res = mysqli_query($mysqli, $sql);
//
//    if ($res === TRUE) {
//        echo "A record has been inserted.";
//    } else {
//        printf("Could not insert record: %s\n", mysqli_error($mysqli));
//    }
//
//    mysqli_close($mysqli);
//}

?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<meta content = "width=device-width, initial-scale=1.0" name = "viewport">
	<meta content = "Sean Tyler Slaughter" name = "author">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "any" type = "image/svg+xml">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "16x16" type = "image/svg+xml">
	<title> Database | Sean Slaughter</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">

	<style>

		  main
		  {
			  min-height: 100vh;
		  }

		  @media screen and (min-width: 1500px)
		  {
			  #myForm
			  {
				  position: fixed;
				  left: 1rem;
				  top: 6rem;
				  max-width: 300px;
				  height: auto;
				  margin: auto;
				  justify-content: left;
			  }
		  }

	</style>

</head>
<body>
	<nav id = "navbar"></nav>

	<main class = "page" id = "database">
		<header>
			<h1>Database Connection</h1>
			<p>Showcase of proper connection and creation to a MySql database</p>
		</header>
		<section id = "myForm" class = "container">
			<h1>Add a Person</h1>
			<form method = "POST" action = "<?= $_SERVER['PHP_SELF'] ?>" enctype = "multipart/form-data">
				<div class = "input-group">
					<label for = "fname">First Name</label>
					<input class = "input" id = "fname" name = "fname" type = "text">
				</div>
				<div class = "input-group">
					<label for = "lname">Last Name</label>
					<input class = "input" id = "lname" name = "lname" type = "text">
				</div>
				<div class = "input-group">
					<label for = "email">Email</label>
					<input class = "input" id = "email" name = "email" type = "email">

				</div>
				<input class = "btn" type = "submit" value = "Submit">
			</form>
		</section>
		<section>
			<h2>Entries</h2>
			<table class = "table">
				<thead>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
				</tr>
				</thead>
				<tbody>
				<!--                        --><?php
                        //                        $mysqli = mysqli_connect("127.0.0.1", "
                        //seanljvy_stslaug", "Darkose123!", "`seanljvy_main_db`");
                        //                        if (mysqli_connect_errno()) {
                        //                            printf("Connect failed: %s\n", mysqli_connect_error());
                        //                            exit();
                        //                        } else {
                        //                            $sql = "SELECT * FROM testTable";
                        //                            $res = mysqli_query($mysqli, $sql);
                        //                            if ($res === TRUE) {
                        ////                                while ($row = mysqli_fetch_array($res)) {
                        ////                                    echo "<tr>";
                        ////                                    echo "<td>" . $row['testField'] . "</td>";
                        ////                                    echo "<td>" . $row['testField2'] . "</td>";
                        ////                                    echo "<td>" . $row['testField3'] . "</td>";
                        ////                                    echo "</tr>";
                        ////                                }
                        //                            } else {
                        //                                printf("Could not get records: %s\n", mysqli_error($mysqli));
                        //                            }
                        //                            mysqli_close($mysqli);
                        //                        }
                        //                        ?>
				</tbody>
			</table>
		</section>

	</main>
	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>

