<header>
	<a href="/"><h1>Camagru</h1></a>
	<div class="connection-block">
		<?php
		echo '<a href="#">Gallery</a>';
		if (isset($_SESSION['user'])) {
				echo '<a href="/montage.php">Take a shot!</a>';
				echo '<a href="/logout.php">Log out</a>';
			}
			else {
				echo '<a href="/client/forms/signup-form.php">Sign up</a>';
				echo '<a href="/client/forms/login-form.php">Log in</a>';
			}
		?>
	</div>
</header>
