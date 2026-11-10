<?php echo View::forge('global/header'); ?>

<div class="container">
	<?php echo View::forge('components/pagination')->set_safe('pagination', $pagination); ?>

	<?php foreach($rankings as $rank): ?>
		<div class="well">
		<div class="row">
			<div class="col-xs-2 text-center">
			<div style="    font-size: 40px;margin-bottom: -20px;"><?php echo number_format($rank['ranking']); ?></div>
			</div>
			<div class="col-xs-10">
				<p style="margin:0"><a href="<?php echo Uri::create('profile/' . $rank['username']); ?>"><?php echo $rank['username']; ?></a></p>
				<small><?php echo number_format($rank['ranking_points']); ?> points - <?php echo number_format($rank['knowledge_points']); ?> points</small>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
		<?php echo View::forge('components/pagination')->set_safe('pagination', $pagination); ?>
</div>

<?php echo View::forge('global/footer'); ?>
