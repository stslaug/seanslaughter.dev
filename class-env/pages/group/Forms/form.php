<?php
$person = $_POST['person'] ?? '';
$option = $_POST['option'] ?? '';
$text = $_POST['text'] ?? '';
$url = $_POST['url'] ?? '';
$color = $_POST['color'] ?? '';
$textarea = $_POST['textarea'] ?? '';
$password = $_POST['password'] ?? '';
$hidden = $_POST['hidden'] ?? 'hidden-value';
$radio = $_POST['radio'] ?? '';
$checkboxes = $_POST['checkboxes'] ?? [];


$fileName = !empty($_FILES['file']['name']) ? $_FILES['file']['name'] : ($_POST['fileName'] ?? '');
$fileSize = !empty($_FILES['file']['size']) ? $_FILES['file']['size'] : ($_POST['fileSize'] ?? '');
$fileType = !empty($_FILES['file']['type']) ? $_FILES['file']['type'] : ($_POST['fileType'] ?? '');


if (!is_array($checkboxes)) {
    $checkboxes = [$checkboxes];
}
?>
<!DOCTYPE html>
<head>
	<meta charset = "UTF-8">
	<meta content = "ie=edge" http-equiv = "X-UA-Compatible">
	<meta content = "width=device-width, initial-scale=1.0" name = "viewport">
	<meta content = "Sean Tyler Slaughter" name = "author">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "any" type = "image/svg+xml">
	<link href = "/assets/images/icon.svg" rel = "icon" sizes = "16x16" type = "image/svg+xml">
	<title>Group Forms | Sean Slaughter</title>
	<link href = "/styles/general.css" rel = "stylesheet">
	<link href = "/styles/buttons.css" rel = "stylesheet">
	<link href = "/styles/input.css" rel = "stylesheet">

</head>
<body>
	<nav id = "navbar"></nav>
	<header class = "container" style = "margin-bottom: -2rem;">
		<h1>Forms with PHP</h1>
		<p>
			Worked on by <b>Wilson Collins</b> and <b>Sean Slaughter</b> <br>
			Utilizes PHP to Store your data and display it. Handles more complex types, such as files.
			The Data can be cleared and access at the bottom of the form! </p>
	</header>

	<form id = "mainForm" class = "container" method = "POST" action = "<?= $_SERVER['PHP_SELF'] ?>" enctype = "multipart/form-data">
		<!-- Text Input -->
		<div class = "input-group">
			<label for = "text">Text Input:</label>
			<input class = "input" id = "text" name = "text" placeholder = "This is an example of a text box!" value = "<?= htmlspecialchars($text) ?>" type = "text">
		</div>

		<!-- Textarea Input -->
		<div class = "input-group">
			<label for = "textarea">Textarea Input:</label>
			<textarea class = "input" id = "textarea" rows = "5" cols = "33" name = "textarea" placeholder = "This is an example of a text area"><?= htmlspecialchars($textarea) ?></textarea>
		</div>

		<!-- Password Input -->
		<div class = "input-group">
			<label for = "password">Password Input:</label>
			<input class = "input" id = "password" name = "password" type = "password" placeholder = "Enter your password">
		</div>
		<div class = "split">

			<!-- Radio Buttons -->
			<div class = "input-group">
				<label>Radio Buttons:</label>
				<div class = "radio-group">
					<div>
						<label for = "radio-1">Radio 1</label>
						<input class = "input" id = "radio-1" name = "radio" type = "radio" value = "radio1" <?php if ($radio === "radio1") echo 'checked'; ?>>
					</div>

					<div>
						<label for = "radio-2">Radio 2</label>
						<input class = "input" id = "radio-2" name = "radio" type = "radio" value = "radio2" <?php if ($radio === "radio2") echo 'checked'; ?>>
					</div>

					<div>
						<label for = "radio-3">Radio 3</label>
						<input class = "input" id = "radio-3" name = "radio" type = "radio" value = "radio3" <?php if ($radio === "radio3") echo 'checked'; ?>>
					</div>

					<div>
						<label for = "radio-4">Radio 4</label>
						<input class = "input" id = "radio-4" name = "radio" type = "radio" value = "radio4" <?php if ($radio === "radio4") echo 'checked'; ?>>
					</div>
				</div>
			</div>

			<!-- Array of Checkboxes -->
			<div class = "input-group">
				<label>Array of Checkboxes</label>
				<div class = "checkbox-group">
                            <?php for ($i = 1; $i <= 6; $i++): ?>
					    <div class = "checkbox-item">
						    <input class = "input" id = "box-<?= $i ?>" name = "checkboxes[]" type = "checkbox" value = "<?= $i ?>" <?= in_array((string)$i, $checkboxes) ? 'checked' : '' ?>>
						    <label for = "box-<?= $i ?>">Box <?= $i ?></label>
					    </div>
                            <?php endfor; ?>
				</div>
			</div>
		</div>

		<!-- Selection List -->
		<div class = "input-group">
			<label for = "option">Select an option:</label>
			<select class = "input" id = "option" name = "option">
				<option value = "" disabled <?= empty($option) ? 'selected' : '' ?>>Choose an option</option>
                      <?php for ($i = 1; $i <= 4; $i++): ?>
				    <option value = "option<?= $i ?>" <?= ($option === "option{$i}") ? 'selected' : '' ?>>Option <?= $i ?></option>
                      <?php endfor; ?>
			</select>
		</div>

		<!-- File Input -->
		<div class = "input-group">
			<label for = "file">Upload a file:</label>
			<input class = "input" id = "file" name = "file" type = "file">
			<input type = "hidden" id = "fileName" name = "fileName" value = "<?= htmlspecialchars($fileName) ?>">
			<input type = "hidden" id = "fileSize" name = "fileSize" value = "<?= htmlspecialchars($fileSize) ?>">
			<input type = "hidden" id = "fileType" name = "fileType" value = "<?= htmlspecialchars($fileType) ?>">
		</div>

		<!-- URL Input -->
		<div class = "input-group">
			<label for = "url">Insert a URL:</label>
			<input autocomplete = "off" class = "input" id = "url" value = "<?= htmlspecialchars($url) ?>" name = "url" placeholder = "ex. https://www.google.com" type = "url">
		</div>

		<!-- Color Input -->
		<div class = "input-group">
			<label for = "color">Favorite color:</label>
			<input autocomplete = "off" class = "input" id = "color" value = "<?= htmlspecialchars($color) ?>" name = "color" placeholder = "Click me" type = "color">
		</div>

		<!-- Submit/Clear Button -->
		<div class = "split" style = "margin-top: 2rem;">
			<a class = "btn" href = "<?= $_SERVER['PHP_SELF'] ?>" type = "button">Clear Values</a>
			<input class = "btn" type = "submit" style = "font-size: 1rem;" value = "Save Data">
		</div>
	</form>
	<!-- Form Data Display Section -->
	<div class = "form-data container">
		<h2>Submitted Form Data</h2>
		<ul>

                <?php if (!empty($text)): ?>
			    <li>Text: <?= htmlspecialchars($text) ?></li>
                <?php endif; ?>
                <?php if (!empty($textarea)): ?>
			    <li>Textarea: <?= htmlspecialchars($textarea) ?></li>
                <?php endif; ?>
                <?php if (!empty($password)): ?>
			    <li>Password: <?= str_repeat('*', strlen($password)) ?></li>
                <?php endif; ?>
                <?php if (!empty($radio)): ?>
			    <li>Radio Selection: <?= htmlspecialchars($radio) ?></li>
                <?php endif; ?>
                <?php if (!empty($option)): ?>
			    <li>Dropdown Selection: <?= htmlspecialchars($option) ?></li>
                <?php endif; ?>
                <?php if (!empty($url)): ?>
			    <li>URL: <?= htmlspecialchars($url) ?></li>
                <?php endif; ?>
                <?php if (!empty($color)): ?>
			    <li>Color:
			    <span style = "display:inline-block;width:20px;height:20px;background-color:<?= htmlspecialchars($color) ?>"></span> <?= htmlspecialchars($color) ?>
			    </li><?php endif; ?>

                <?php if (!empty($checkboxes)): ?>
			    <li>Checkboxes Selected:
				    <ul>
                                <?php foreach ($checkboxes as $box): ?>
						  <li>Box <?= htmlspecialchars($box) ?></li>
                                <?php endforeach; ?>
				    </ul>
			    </li>
                <?php endif; ?>

                <?php if (!empty($fileName)): ?>
			    <li>File Information: (All passed as hidden-values)
				    <ul>
					    <li>Name: <?= htmlspecialchars($fileName) ?></li>
					    <li>Size: <?= htmlspecialchars($fileSize) ?> bytes</li>
					    <li>Type: <?= htmlspecialchars($fileType) ?></li>
				    </ul>
			    </li>
                <?php endif; ?>
		</ul>
	</div>
	<footer id = "footer"></footer>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script rel = "text/javascript" src = "/nav-foot.js"></script>
	<script>

          jQuery(function () {
              $('#file').on('change', function () {
                  if (!this.files[0]) {
                      return;
                  }
                  if (this.files && this.files[0]) {
                      const file = this.files[0];
                      $('#fileName').val(file.name);
                      $('#fileSize').val(file.size);
                      $('#fileType').val(file.type);
                  }
              });

          });
	</script>
</body></html>