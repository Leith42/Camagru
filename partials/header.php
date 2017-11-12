<header>
	<a href="/"><h1>Camagru</h1></a>
	<div class="connection-block">
		<?php
			if (isset($_SESSION['user'])) {
				echo $_SESSION['user'] . '<br />';
				echo '<a href="/logout.php">Log out</a>';
			}
			else {
				echo '<a href="/forms/signup.php">Sign up</a>';
				echo ' | ';
				echo '<a href="/forms/login.php">Log in</a>';
			}
		?>
	</div>
</header>
