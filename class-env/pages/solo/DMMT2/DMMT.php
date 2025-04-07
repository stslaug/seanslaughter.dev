<?php
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
	<title>DMMT2 | Sean Slaughter</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">
	<link href = "/styles/tables.css" rel = "stylesheet">

	<style>
		  .bad
		  {
			  background-color: var(--red);
			  color: white;
			  border-radius: var(--curve);
			  float: right;
		  }

		  .good
		  {
			  background-color: var(--green);
			  color: black;
			  border-radius: var(--curve);
			  float: right;
		  }
	</style>
</head>
<body>
	<nav id = "navbar"></nav>
	<header class = "page">
		<h1> Dont Make Me Think Examples</h1>
	</header>
	<main class = "page" id = "DMMT">
		<section class = "container">
			<span class = "bubble bad">Bad</span>
			<h1> Rule 1 : Accessibility Considerations</h1>
			<h2><a href = "https://www.pnwx.com/" target = "_blank">www.pnwx.com/</a></h2>
			<p> We can ignore so many horrible things about this site. But there is a clear lack of
				accessibility standards being implemented. Text is hard to read with background that does not
				mesh well with black in some areas. No Navbar. Pressing Tab to navigate through the site is
				not possible (Atleast to explore other parts of the site that is not "the most popular items").</p>
			<iframe src = "https://www.pnwx.com/" width = "100%" height = "500px"></iframe>
		</section>
		<section class = "container">
			<span class = "bubble bad">Bad</span>
			<h1> Rule 2: Website Conventions</h1>
			<h2><a href = "https://thebiguglywebsite.com/" target = "_blank">www.thebiguglywebsite.com/</a>
			</h2>
			<p> This website is intentionally bad. I am going to ding this one for no navbar so it is really
				easy to get stuck on some pages, if you don't know exactly the incosistent link back to the
				home.
				The colors are wild/seemingly random, images, aren't loading (at least they put text). On top
				of this, some links dont even have actual destinations. </p>
			<iframe src = "https://thebiguglywebsite.com/" width = " 100%
			" height = "500px"
			></iframe>
		</section>

		<section class = "container">
			<span class = "bubble bad">Bad</span>
			<h1> Rule 3: Designed to read not scan</h1>
			<h2><a href = "https://www.007museum.com/" target = "_blank">www.007museum.com/</a></h2>
			<p> Unfortunately, this site is designed to be read, not scanned. The text is hard to read since it
				is one giant page, that spans THOUSANDS of lines, the text is very small, and the images are
				very large, and the content is not centerted well. There is no easy way to parse this
				information, or find what you need.
				<iframe src = "https://www.007museum.com/" width = "100%" height = "500px"></iframe>
		</section>

		<section class = "container">
			<span class = "bubble good">Good</span>
			<h1> Rule 4: Clear Sections </h1>
			<h2><a href = "https://www.nytimes.com/" target = "_blank">https://www.nytimes.com/</a></h2>
			<p> The website is very clear, and the sections are easy to find, There are horizontal lines, with
				their titles labelled such as "Middle East Tensions"
				<br>
				Then When you need to dive deeper, those naviagtion buttons are in view connected to that
				section, with descriptive labels

			</p>

			<iframe src = "https://www.nytimes.com/" width = "100%" height = "500px"></iframe>
		</section>
		<section class = "container">
			<span class = "bubble good">Good</span>
			<h1> Rule 5: Get to the point </h1>
			<h2><a href = "https://www.shademaster.com.au/ target = " _blank">https://www.shademaster.com.au/</a>
			</h2>
			<p> This is a good example for "getting to the point." Reason being the moment I get to the screen,
				it tells me EXACTLY what it could do, and an immediate "Locate Dealers" button in the center
				of the screen. </p>
			<iframe src = "https://www.shademaster.com.au/" width = " 100%
			" height = "500px"
			></iframe>
		</section>
		<section class = "container">
			<span class = "bubble good">Good</span>
			<h1> Rule 6: Users don't like to wait </h1>
			<h2>
				<a href = "https://seanslaughter.dev/" target = "_blank">https://seanslaughter.dev/</a>
			</h2>
			<p>
				My Website is lightweight compared to other sites, BUT I have still taken steps to reduce my
				load times to ensure google prioritization <br> I have a speed index of 1.1s, with me
				exceeding most metrics. This ensures users do not wait for my site to load. </p>

			<iframe src = "https://seanslaughter.dev/" width = "100%" height = "500px"></iframe>
	</main>

	<footer id = "footer"></footer>

	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
</body>

</html>

