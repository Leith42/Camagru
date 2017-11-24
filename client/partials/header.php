<header>
	<a href="/"><h1>Camagru</h1></a>
	<div class="connection-block">
		<?php
		echo '<a href="#">Gallery</a>';
		if (isset($_SESSION['user'])) {
				echo '<a href="/client/shot.php">Take a shot!</a>';
				echo '<a href="/server/logout.php">Log out</a>';
			}
			else {
				echo '<a href="/client/forms/signup-form.php">Sign up</a>';
				echo '<a href="/client/forms/login-form.php">Log in</a>';
			}
		?>
	</div>
</header>
