<?php echo View::forge('global/header'); ?>

<div class="container">
  <form method="post">
    <div class="row">
      <?php foreach($hacker as $field => $value): ?>
        <div class="col-md-6">
          <label><?php echo $field; ?></label>
          <input type="text" name="<?php echo $field; ?>" value="<?php echo $value; ?>" class="form-control" placeholder="<?php echo $field; ?>"/>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-block">update</button>
  </form>
</div>

<?php echo View::forge('global/footer'); ?>
