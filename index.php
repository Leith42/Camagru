<?php
session_start();
require_once('autoload.php');
use classes\Users;
use classes\UserManager;
use classes\Database;

?>
<!DOCTYPE html>
<html lang="en">
<?php include"./partials/head.html"; ?>
<body class="center">
<?php include"./partials/header.php"; ?>
<!--	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. <b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b>. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>-->
<!--	<p>Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor. Morbi lectus risus, iaculis vel, suscipit quis, luctus non, massa. Fusce ac turpis quis ligula lacinia aliquet. Mauris ipsum. </p>-->
<!--	<p><b>Curabitur sodales ligula in libero</b>. Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. <i>Lorem ipsum dolor sit amet, consectetur adipiscing elit</i>. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. <b>Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis</b>. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. </p>-->
<!--	<p>Sed lectus. Integer euismod lacus luctus magna. Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi lacinia molestie dui. Praesent blandit dolor. Sed non quam. In vel mi sit amet augue congue elementum. Morbi in ipsum sit amet pede facilisis laoreet. <i>Ut fringilla</i>. Donec lacus nunc, viverra nec, blandit vel, egestas et, augue. Vestibulum tincidunt malesuada tellus. Ut ultrices ultrices enim. <b>Suspendisse in justo eu magna luctus suscipit</b>. Curabitur sit amet mauris. </p>-->
<!--	<p>Morbi in dui quis est pulvinar ullamcorper. Nulla facilisi. Integer lacinia sollicitudin massa. <b>Quisque cursus, metus vitae pharetra auctor, sem massa mattis sem, at interdum magna augue eget diam</b>. Cras metus. Sed aliquet risus a tortor. Integer id quam. Morbi mi. <b>Morbi in dui quis est pulvinar ullamcorper</b>. Quisque nisl felis, venenatis tristique, dignissim in, ultrices sit amet, augue. Proin sodales libero eget ante. Nulla quam. Aenean laoreet. </p>-->
<!--	<br />-->
<?php include"./partials/footer.html"; ?>
</body>
</html>