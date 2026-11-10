<?php echo View::forge('global/header'); ?>

<div class="container">
  <?php foreach($hackers as $k => $h): ?>
    <div class="text-<?php echo $k % 2 == 0 ? 'leftl' : 'right'; ?>">
      <p><a href="<?php echo Uri::create('profile/' . $h['username']); ?>"><?php echo $h['username']; ?></a> - level <?php echo $h['level']; ?></p>
      <small>C(<?php echo $h['created_at'] ;?>) - A(<?php echo $h['last_active'] ;?>)</small>
    </div><br/>
  <?php endforeach; ?>
  <br/>
  <div class="text-center">
    <?php echo str_replace(array('<div', '<span'), array('<ul', '<li'), html_entity_decode($pagination)); ?>
  </div>
</div>

<?php echo View::forge('global/footer'); ?>
