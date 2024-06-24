<?php

$site_name = null;
$site_logo = null;
$site_description = null;
if(!empty($site) && $site instanceof \Mini\Cms\Modules\Site\Site) {
    $site_name = $site->getBrandingAssets('Name');
    $site_logo = $site->getBrandingAssets('Logo');
    if(!empty($site_logo['fid'])) {
        $file = \Mini\Cms\Modules\FileSystem\File::load($site_logo['fid']);
        $site_logo = $file?->getFilePath();
    }
    $site_description = $site->getBrandingAssets('Slogan');
}
?>
<div id="header-wrap">

    <div class="top-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="social-links">
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href="#"><i class="icon icon-facebook"></i></a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#"><i class="icon icon-twitter"></i></a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#"><i class="icon icon-youtube-play"></i></a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#"><i class="icon icon-behance-square"></i></a>-->
<!--                            </li>-->
<!--                        </ul>-->
                    </div><!--social-links-->
                </div>
                <div class="col-md-6">
                    <div class="right-element">
                        <?php if(!empty($current_user) && $current_user instanceof \Mini\Cms\Modules\CurrentUser\CurrentUser && !empty($current_user->id())): ?>
                        <a href="/user" class="user-account for-buy"><i
                                    class="icon icon-user"></i><span>Account</span></a>
                        <a href="#" class="cart for-buy"><i class="icon icon-clipboard"></i><span>Cart:(0
									$)</span></a>
                        <?php endif; ?>

                        <div class="action-menu">

                            <div class="search-bar">
                                <a href="#" class="search-button search-toggle" data-selector="#header-wrap">
                                    <i class="icon icon-search"></i>
                                </a>
                                <form role="search" method="get" class="search-box">
                                    <input class="search-field text search-input" placeholder="Search"
                                           type="search">
                                </form>
                            </div>
                        </div>

                    </div><!--top-right-->
                </div>
            </div>
        </div>
    </div><!--top-content-->

    <header id="header">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-2">
                    <div class="main-logo">
                        <a href="/"><img src="/<?= $site_logo ?>" alt="logo"></a>
                    </div>

                </div>
                <?php if(!empty($content) && $content instanceof \Mini\Cms\Theme\Menus): ?>
                <div class="col-md-10">
                    <nav id="navbar">
                        <div class="main-menu stellarnav">
                            <ul class="menu-list">
                                <?php foreach ($content->getMenus() as $menu_key=>$menu): ?>
                                    <?php  if($menu instanceof \Mini\Cms\Theme\Menu): ?>
                                        <?php
                                        $attributes = $menu->getAttributes();
                                        $title = $attributes['title'] ?? null;
                                        $id = $attributes['id'] ?? null;
                                        $classes = $attributes['class'] ?? null;
                                        $target = $attributes['target'] ?? null;
                                        $options = $menu->getOptions();
                                        $active_class = !empty($options['active']) ? 'active' : null;
                                        ?>
                                        <li class="menu-item">
                                            <a href="<?= $menu->getLink(); ?>"
                                               id="<?= $id; ?>"
                                               target="<?= $target; ?>"
                                               title="<?= $title; ?>"
                                               class="<?= $classes. ' '.$active_class; ?> nav-link">
                                                <?= $menu->getLabel(); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>

                            <div class="hamburger">
                                <span class="bar"></span>
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </div>

                        </div>
                    </nav>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

</div>