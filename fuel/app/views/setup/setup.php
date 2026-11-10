<?php echo View::forge('global/header', array('title' => 'Setup', 'hide_menu' => true)); ?>

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <h1 class="text-center">Secret Republic setup</h1>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
      <?php endif; ?>

      <?php if ($show_form): ?>
      <form method="post" autocomplete="off">
        <?php if ($configured): ?>
          <p class="text-center">
            Database connection found in <code>fuel/app/config/db.php</code>.
            Create the admin account to import the game data.
          </p>
        <?php else: ?>
          <h3>Database</h3>
          <p>Use an <strong>empty</strong> MySQL database. The setup imports all tables and seed data into it.</p>
          <div class="row">
            <div class="col-sm-8 form-group">
              <label for="db_host">Host</label>
              <input type="text" class="form-control" id="db_host" name="db_host" required value="<?php echo e(Input::post('db_host', $defaults['db_host'])); ?>"/>
            </div>
            <div class="col-sm-4 form-group">
              <label for="db_port">Port</label>
              <input type="number" class="form-control" id="db_port" name="db_port" min="1" max="65535" required value="<?php echo e(Input::post('db_port', $defaults['db_port'])); ?>"/>
            </div>
          </div>
          <div class="form-group">
            <label for="db_name">Database name</label>
            <input type="text" class="form-control" id="db_name" name="db_name" required value="<?php echo e(Input::post('db_name', $defaults['db_name'])); ?>"/>
          </div>
          <div class="row">
            <div class="col-sm-6 form-group">
              <label for="db_user">User</label>
              <input type="text" class="form-control" id="db_user" name="db_user" required value="<?php echo e(Input::post('db_user', $defaults['db_user'])); ?>"/>
            </div>
            <div class="col-sm-6 form-group">
              <label for="db_password">Password</label>
              <input type="password" class="form-control" id="db_password" name="db_password"/>
            </div>
          </div>
        <?php endif; ?>

        <h3>Admin account</h3>
        <div class="form-group">
          <label for="admin_username">Username <small>(letters and digits)</small></label>
          <input type="text" class="form-control" id="admin_username" name="admin_username" required maxlength="30" pattern="[A-Za-z0-9]+" value="<?php echo e(Input::post('admin_username', '')); ?>"/>
        </div>
        <div class="form-group">
          <label for="admin_password">Password <small>(5-30 characters)</small></label>
          <input type="password" class="form-control" id="admin_password" name="admin_password" required minlength="5" maxlength="30"/>
        </div>
        <div class="form-group">
          <label for="admin_email">Email</label>
          <input type="email" class="form-control" id="admin_email" name="admin_email" required value="<?php echo e(Input::post('admin_email', '')); ?>"/>
        </div>

        <button type="submit" class="btn btn-default btn-block">install</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php echo View::forge('global/footer'); ?>
