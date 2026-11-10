<?php echo View::forge('global/header'); ?>


<div class="container text-center">
	<div class="row">
		<?php if (Hacker::can('manage_missions')): ?>
			<div class="col-xs-3">
				<a href="<?php echo Uri::create('cardinal/missions'); ?>"><h1><i class="fa fa-crosshairs" aria-hidden="true"></i></h1></a>
			</div>
		<?php endif; ?>
		<?php if (Hacker::group() == 2): ?>
			<div class="col-xs-3">
				<a href="<?php echo Uri::create('cardinal/online'); ?>"><h1><i class="fa fa-user-o" aria-hidden="true"></i></h1></a>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo Uri::create('cardinal/players'); ?>"><h1><i class="fa fa-users" aria-hidden="true"></i></h1></a>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo Uri::create('cardinal/tutorial'); ?>"><h1><i class="fa fa-graduation-cap" aria-hidden="true"></i></h1></a>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo Uri::create('cardinal/feedback'); ?>"><h1><i class="fa fa-smile-o" aria-hidden="true"></i></h1></a>
			</div>
		<?php endif ;?>
</div>

<?php echo View::forge('global/footer'); ?>
