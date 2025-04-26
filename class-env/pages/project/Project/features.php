<?php
session_start();
?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset = "utf-8">
	<meta content = "width=device-width, initial-scale=1, shrink-to-fit=no" name = "viewport">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">

	<title>Features | Most Likely To</title>

	<style>
		  .game-container h4, h3
		  {
			  color: var(--white);
		  }

		  .feature-section div
		  {
			  padding: 0.5rem;
			  background-color: rgba(255, 255, 255, 0.1);
			  width: min-content;
			  min-width: 400px;
			  border-radius: 1rem;
			  margin: auto auto 0.5rem auto;
		  }

		  @media (max-width: 850px)
		  {
			  .split
			  {
				  flex-direction: column;
				  align-items: center;
			  }


			  .split .feature-section
			  {
				  width: 100%;
				  max-width: 100%;
				  min-width: 100%;
			  }
		  }
	</style>
</head>
<body id = "game-body">
	<nav>
		<div id = "navbar-container"></div>
		<div id = "game-nav" class = "container-fluid"></div>
	</nav>

	<main class = "game-container" style = "max-width: 1000px;">
		<h1>Team Project | Features</h1>
		<h2>CPSC 3750 – Web App Development</h2>

		<div class = "section">
			<h3>Team Members</h3>
			<ul>
				<li>Wilson Collins</li>
				<li>Sean Slaughter</li>
			</ul>
		</div>
		<div style = "max-width: 800px" class = "section">
			<h3>Project Description</h3>
			<p>The Most Likely To project is a web application that allows users to play in games with custom, or premade questions.
				The players vote on which player is most likely to match each question</p>
			<div class = "split">
				<div class = "feature-section" id = "frontend-section">
					<h3>Frontend Features</h3>

					<div>
						<h4>Gameplay ✅</h4>
						<p>The biggest challenge for this was to ensure page styling consistency, across each page. These pages should also be mobile friendly too!</p>
					</div>

					<div>
						<h4>Custom Questions ✅</h4>
						<p>New page with a textarea input for users to add custom questions, as long as they are logged in!</p>
					</div>

					<div>
						<h4>Login/logout/register ✅</h4>
						<p>New page for login and registration.</p>
					</div>
					<div>
						<h4>Sub-Navigation Bar ✅</h4>
						<p>A Subnav bar to allow for easier navigation between pages isolated within this project.</p>
					</div>
				</div>

				<div class = "feature-section" id = "backend-section">
					<h3>Backend Features</h3>

					<div>
						<h4>Profile Statistics ✅</h4>
						<p>Pulls and calculates statistics for the user's profile, from their gameplay history, votes cast and received, and more.</p>
					</div>

					<div>
						<h4>Databases and Gameplay ✅</h4>
						<p>Took the game from local file interaction to a MySQL database for better scalability and security.</p>
					</div>

					<div>
						<h4>Login/logout/register ✅</h4>
						<p>Securely logs users in and out of the application. Hashes passwords with bcrypt, and utilizes PHP for server side handling of this data. </p>
					</div>
				</div>
			</div>
			<section class = "feature-section" id = "incomplete-section">
				<h3>Planned Features</h3>

				<div>
					<h4>Custom Question Removal and Editing ⚠️</h4>
					<p>This feature is halfway implemented. You currently can create new sets, however editing and deleting questions/sets is not yet implemented.</p>
				</div>

				<div>
					<h4>Forgotten Password ❌</h4>
					<p>We implemented a security question and answer system to allow users to reset their passwords if they forget it. However with time constraints, we decided to leave this feature out of the project.</p>
				</div>

				<div>
					<h4>Friends List ❌</h4>
					<p>This was a reach goal for us, having a friends list would have been so helpful for joining games. However, again with time, we decided to leave this feature out of the project.</p>
				</div>
			</section>
		</div>

	</main>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "scripts/includes.js"></script>

</body>