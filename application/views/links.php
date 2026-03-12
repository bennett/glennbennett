<section id="content">
  <div class="content-wrap py-4">
    <div class="container">
      <div class="row g-3">
        <?php foreach($links as $link): ?>
          <div class="col-md-4 col-sm-6">
            <div class="card h-100">
              <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                  <i class="<?php echo isset($link['icon']) ? $link['icon'] : 'icon-line-link'; ?> me-2"></i>
                  <h6 class="mb-0">
                    <?php echo $link['title']; ?>
                  </h6>
                </div>
                <p class="card-text small text-muted mb-0">
                  <?php echo $link['description']; ?>
                </p>
                <a href="<?php echo $link['url']; ?>" class="stretched-link"></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
