<?php
// This file will be loaded INSTEAD of your theme for the selected page/post.
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo esc_html(get_the_title()); ?></title>
    <?php wp_head(); ?>
</head>
<body>
    <div id="swiftmove-packers-root">
        <?php include dirname(__FILE__) . '/template.php'; ?>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
