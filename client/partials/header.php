<header>
	<script type="text/javascript">
        function menu() {
            var nav = document.getElementById("myTopnav");
            var title =  document.getElementsByTagName('h1')[0];

            if (title) {
                console.log(title.display);
			}
            if (nav.className === "topnav") {
                nav.className += " responsive";
                title.style.display = 'none';
            } else {
                nav.className = "topnav";
                title.style.display = 'block'
            }
        }
	</script>
	<div class="topnav" id="myTopnav">
		<h1>Camagru</h1>
		<?php
		if (isset($_SESSION['user'])) {
			echo '<a href="/server/logout.php">Log out</a>';
			echo '<a href="/client/shot.php">Take a shot!</a>';
		} else {
			echo '<a href="/client/forms/login-form.php">Log in</a>';
			echo '<a href="/client/forms/signup-form.php">Sign up</a>';
		}
		echo '<a href="/client/gallery.php">Gallery</a>';
		if (isset($_SESSION['user'])) {
			echo '<a href="/client/personal.php">' . $_SESSION['user'] . '</a>';
		}
		echo '<a href="javascript:void(0);" class="icon" onclick="menu()">&#9776;</a>';
		?>
	</div>
</header>
