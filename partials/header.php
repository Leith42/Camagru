<header>
	<a href="/"><h1>Camagru</h1></a>
	<div class="connection-block">
		<?php
			if (isset($_SESSION['user'])) {
				echo $_SESSION['user'] . '<br />';
				echo '<a href="#">Log out</a>';
			}
			else {
				echo '<a href="/forms/signup-form.php">Sign up</a>';
				echo ' | ';
				echo '<a href="#">Log in</a>';
			}
		?>
	</div>
</header>
