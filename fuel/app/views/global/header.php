<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo Config::get('title'); ?></title>
		<link rel="shortcut icon" href="<?php echo Uri::create('assets/img/favicon.ico'); ?>" type="image/x-icon">
		<?php if (!Hacker::check()): ?>
			<meta name="description" content="Secret Republic is a free online cyber security role playing game that runs on any device. Break into simulated servers, crack passwords and query databases in a multiplayer world.">
			<meta name="revisit" content="After 3 days">
			<meta name="Expires" content="never">
			<meta name="robots" content="INDEX,FOLLOW">
			<meta name="language" content="en">
			<meta name="page-type" content="browser game, browsergame, game">
			<meta name="author" content="Secret Republic">
			<meta name="publisher" content="Secret Republic">
			<meta name="copyright" content="Secret Republic">
			<meta name="page-topic" content="free online cyber security simulation browser based role playing game"> <meta name="audience" content="all">
		<?php endif; ?>
	  <?php echo Asset::css('reset.css'); ?>
	  <?php echo Asset::css('bootstrap.min.css'); ?>
		<?php echo Asset::css('style.css'); ?>

		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,600&amp;subset=latin-ext" rel="stylesheet">
	</head>
	<body class="noselect">
		<?php if (Hacker::check()):

		 	// echo View::forge('tutorial/tutorial-handler');
			 $convs = Hacker::unread_conversations();
			 $rewards = Hacker::unclaimed_rewards();
		?>

		<h2 class="level">
			L<?php echo Hacker::get('level'); ?>
		</h2>

	 <div class="toolbar-top-right">
			<i class="fa fa-cube"></i> <?php echo number_format(Hacker::get('money')); ?>
		</div>
	<?php endif; ?>

			<?php if (!isset($hide_menu)): ?>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			<nav class="navbar navbar-default navbar-fixed-bottom">
			  <div class="container">

			    <div class="collapse navbar-collapse" id="menu">
			      <ul class="nav navbar-nav text-center" <?php echo !Hacker::check() ? 'style="width:100%;"' : ''; ?>>
							<?php if (Hacker::check()): ?>
			        <li><a href="<?php echo Uri::create('dashboard'); ?>"><i class="fa fa-tachometer" aria-hidden="true"></i></a></li>
							<li><a href="<?php echo Uri::create('quests'); ?>"><i class="fa fa-crosshairs" aria-hidden="true"></i></a></li>
			        <li><a href="<?php echo Uri::create('skills'); ?>"><i class="fa fa-diamond" aria-hidden="true"></i></a></li>
			        <li><a href="<?php echo Uri::create('knowledge'); ?>"><i class="fa fa-book" aria-hidden="true"></i></a></li>
							<li><a href="<?php echo Uri::create('train'); ?>"><i class="fa fa-calculator" aria-hidden="true"></i></a></li>
							<?php if (Hacker::group() == 2): ?>
								<li><a href="<?php echo Uri::create('grid'); ?>"><i class="fa fa-th" aria-hidden="true"></i></a></li>
							<?php endif; ?>
			        <!--<li><a href="<?php echo Uri::create('servers'); ?>"><i class="fa fa-server" aria-hidden="true"></i></a></li>
			        <li><a href="<?php echo Uri::create('shop'); ?>"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
			        <li><a href="<?php echo Uri::create('group'); ?>"><i class="fa fa-users" aria-hidden="true"></i></a></li>-->
						<?php else :?>
							<li style="float:none"><a href="<?php echo Uri::base(); ?>"><i class="fa fa-home" aria-hidden="true"></i></a></li>
							<li style="float:none"><a href="<?php echo Uri::create('create-account'); ?>"><i class="fa fa-id-card" aria-hidden="true"></i></a></li>
							<li style="float:none"><a href="<?php echo Uri::create('world'); ?>"><i class="fa fa-globe" aria-hidden="true"></i></a></li>
							<li style="float:none"><a href="https://www.facebook.com/theSecretRepublic"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li style="float:none"><a href="https://twitter.com/iSecretRepublic"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						<?php endif; ?>

			      </ul>
						<?php if (Hacker::check()): ?>

			      <ul class="nav navbar-nav navbar-right text-center">
								<li <?php echo $convs ? 'class="active"' : ''; ?>><a href="<?php echo Uri::create('conversations'); ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i><?php echo $convs ? ' <small>('.$convs.')</small>' : ''; ?></a></li>
			        	<li <?php echo $rewards ? 'class="active"' : ''; ?>><a href="<?php echo Uri::create('rewards'); ?>"><i class="fa fa-gift" aria-hidden="true"></i><?php echo $rewards ? ' <small>('.$rewards.')</small>' : ''; ?></a></li>

			      		<li><a href="<?php echo Uri::create('rankings'); ?>"><i class="fa fa-trophy" aria-hidden="true"></i></a></li>
								<li><a href="<?php echo Uri::create('dna'); ?>"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
			      </ul>
					<?php endif; ?>

				</div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>


		<?php endif; ?>
		<div class="container-fluid" style="min-height:400px; padding-top:0px;">

			<?php if (!isset($messages_handled)): ?>
				<div class="container">
					<?php echo View::forge('components/messages'); ?>
				</div>
			<?php endif; ?>
