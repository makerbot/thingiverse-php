<?php  
require_once '../thingiverse.php';
$thingiverse = new Thingiverse();
// $thingiverse->redirect_uri = '';
?>
<!DOCTYPE html>
<html>

<head>
	<title>Thingiverse PHP Wrapper Example</title>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		<?php 
		if ($thingiverse->access_token = $_GET['access_token']):
			$thingiverse->getUserLikes();
			var_dump($thingiverse->response_data);
		?>
		<?php  
		elseif ($code = $_GET['code']):
			$thingiverse->oAuth($code);
			echo 'Access Token: ' . $thingiverse->access_token;
			$thingiverse->getUser();
			var_dump($thingiverse->response_data);
		?>
			<a href="/example?access_token=<?php echo $thingiverse->access_token; ?>">See User Likes</a>			
		<?php  
		else:
		?>
			<a href="<?php echo $thingiverse->makeLoginURL(); ?>">Login with Thingiverse</a>
		<?php  
		endif;
		?>
	</div>
</body>

</html>