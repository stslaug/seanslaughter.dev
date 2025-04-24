<!DOCTYPE html>
<html>
<head>

	<style>

		  *
		  {
			  box-sizing: border-box;
			  margin: 0;
			  padding: 0;
		  }

		  body
		  {
			  height: 100vh;
			  width: 100%;
			  display: flex;
			  align-items: center;
			  justify-content: center;
			  overflow: hidden;
			  background: white;
		  }

		  main
		  {
			  background-color: black;
			  margin: auto;
			  width: 800px;
			  height: 800px;
		  }

		  main #grid
		  {
			  width: 100%;
			  aspect-ratio: 1;
			  display: grid;
			  grid-template-columns: repeat(50, 1fr);
			  grid-template-rows: repeat(50, 1fr);
			  overflow: hidden;
		  }

		  main .tile
		  {
			  width: 40px;
			  height: 40px;
			  min-height: 25px;
			  border: 1px solid rgba(255, 255, 255, 0.25);
			  min-width: 25px;
			  color: green;
			  transition: background-color 0.10s ease;
		  }

	</style>

</head>
<body>
	<main>
		<div id = "grid">
			<div class = "tile"></div>
		</div>

	</main>

</body>

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    const grid = $("#grid"),
        tile = $(".tile");

    for (let i = 0; i < 999; i++) {
        let newTile = tile.clone();
        newTile.on('mouseover',
            function () {
                $(this).css("background-color", getRandomColor());
            });
        newTile.on('mouseout',
            function () {

                const tileElement = $(this);
                setTimeout(function () {
                    tileElement.css("background-color", "black");
                }, 200);

            });
        grid.append(newTile);
    }

</script>

</html>
