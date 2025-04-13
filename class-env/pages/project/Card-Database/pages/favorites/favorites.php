<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /class-env/pages/project/Card-Database/auth/login.php");
    exit;
}

$servername = "";
$username = "";
$password = "";
$database = "";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM favorites WHERE user_id = $user_id");
$cards = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<title>Favorites | Card Database</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "../../card.css" rel = "stylesheet">

	<link href = "/styles/subnav.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">

</head>
<body class = "cardBody">
	<nav id = "navbar"></nav>
	<nav class = "subnav">
		<h2>Magic the Gathering Card Viewer | Utilizing
			<a href = "https://scryfall.com/docs/api" target = "_blank">Scryfall API</a></h2>
		<div>
			<a class = "nav-item" href = "../../card-home.php">Cards</a>
			<a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/about/about.php">About</a>
			<a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/stats/stats.php">Stats</a>
                <?php if (isset($_SESSION['username'])): ?>
			    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/favorites/favorites.php">Favorites</a>
			    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/pages/profile/profile.php">Profile</a>
			    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/auth/logout.php">Logout</a>
                <?php else: ?>
			    <a class = "nav-item" href = "/class-env/pages/project/Card-Database/auth/login.php">Login / Sign Up</a>
                <?php endif; ?>

		</div>
	</nav>
	<main style = "margin: auto; text-align: center; max-width: 100%;" class = "page">
		<h1><?php echo htmlspecialchars($_SESSION['username']); ?>'s Favorite Cards</h1>
		<div style = "margin: 1rem auto; max-width: 400px;"> <!--  Search Bar  I am just ready to be done with this so hardcoded teehee-->
			<input class = "input" type = "text" id = "searchFavorites" placeholder = "Search your favorite cards by name...">
		</div>
		<div style = "width: 100%; min-width: 100%;" class = "page" id = "card-viewer"
		>
                <?php foreach ($cards as $card): ?>
			    <div class = "card-wrapper">
				    <img class = "card-btn" src = "<?= htmlspecialchars($card['image_url']) ?>" alt = "<?= htmlspecialchars($card['name']) ?>">
				    <div class = "card-info">
					    <h4><?= htmlspecialchars($card['name']) ?></h4>
					    <div class = "type"><?= htmlspecialchars($card['type_line']) ?></div>
					    <div class = "colors"><?= htmlspecialchars($card['color']) ?></div>

					    <div class = "date">
						    <i class = "fa-solid fa-star"></i><?php echo date("F j, Y | g:i a", strtotime($card['favorited_on'])); ?></p>
					    </div> <!--  Timestamp  -->
				    </div>
				    <div class = "input-group fav-btn">
					    <input class = "fav" type = "checkbox" id = "fav-<?= $card['card_id'] ?>" data-card-id = "<?= $card['card_id'] ?>" checked>
					    <label for = "fav-<?= $card['card_id'] ?>"></label>
				    </div>
			    </div>
                <?php endforeach; ?>
		</div>
	</main>
	<footer id = "footer"></footer>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src = "/nav-foot.js"></script>
	<script src = "favorites.js"></script>

	<script>
          $(document).ready(function () {
              $('#searchFavorites').on('input', function () {
                  const query = $(this).val().toLowerCase();

                  $('.card-wrapper').each(function () {
                      const cardName = $(this).find('h4').text().toLowerCase();

                      if (cardName.includes(query)) {
                          $(this).show();
                      } else {
                          $(this).hide();
                      }
                  });
              });
          });
	</script>

</body>
</html>
