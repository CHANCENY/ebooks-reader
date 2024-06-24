<div class="container">
    <?php if(!empty($content['user']) && $content['user'] instanceof \Mini\Cms\Entities\User): ?>
      <div class="row mt-5">
          <div class="col-md-6 bordered">
              <div class="p-4">
                  <h3><?= $content['user']->getFirstname().' '.$content['user']->getLastname(); ?></h3>
                  <p>
                      Username:&nbsp;&nbsp;<?= $content['user']->getName(); ?> <br>
                      Email:&nbsp;&nbsp;<?= $content['user']->getEmail() ?> <br>
                      Active:&nbsp;&nbsp; <?= $content['user']->getActive() == 1 ? "<i class='badge bg-primary'>Yes</i>" : "<i class='badge bg-danger'>No</i>"; ?>
                  </p>
              </div>
          </div>
          <div class="col-md-6">
              <div>
                  <a class="btn" href="/user/<?= $content['user']->getUid() ?>/edit">Edit Account</a>
              </div>
              <div>
                  <a class="btn" href="/user/<?= $content['user']->getUid() ?>/delete">Delete</a>
              </div>
              <div>
                  <a class="btn" href="/book-shop-dashboard/<?= $content['user']->getUid() ?>">Book Dashboard</a>
              </div>
          </div>
      </div>
    <?php endif; ?>
</div>
