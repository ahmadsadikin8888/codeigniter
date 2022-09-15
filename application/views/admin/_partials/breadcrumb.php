<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <?php
    $n = 0;
    foreach ($this->uri->segments as $segment) : ?>
        <?php
        if ($n > 0) {
            $url = substr($this->uri->uri_string, 0, strpos($this->uri->uri_string, $segment)) . $segment;
            $is_active =  $url == $this->uri->uri_string;
        ?>
            <?php if ($is_active) : ?>
                <h1 class="h3 mb-0 text-gray-800"><?php echo ucfirst($segment) ?></h1>
            <?php else : ?>
                <?php if ($n == 1) : ?>
                    <a href="<?php echo site_url($url) ?>" class="btn btn-primary "><?php echo ucfirst($segment) ?></a>
                <?php endif; ?>
        <?php endif;
        }
        $n++;
        ?>
    <?php endforeach; ?>
</div>