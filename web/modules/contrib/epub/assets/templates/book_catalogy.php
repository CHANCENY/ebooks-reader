<section id="popular-books" class="bookshelf py-5 my-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="section-header align-center">
                    <div class="title">
                        <span>Some quality items</span>
                    </div>
                    <h2 class="section-title">Popular Books</h2>
                </div>
                <?php if(!empty($books)): $categories = array_keys($books); ?>
                    <ul class="tabs">
                        <?php $active = false; foreach ($categories as $category): ?>
                            <?php if(!$active): ?>
                                <li data-tab-target="#<?= str_replace(' ','-', strtolower($category)); ?>" class="active tab"><?= ucfirst($category); ?></li>
                                <?php  $active = true; ?>
                            <?php else: ?>
                                <li data-tab-target="#<?= str_replace(' ','-', strtolower($category)); ?>" class="tab"><?= ucfirst($category); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if(!empty($books)): ?>

                    <div class="tab-content">
                        <?php foreach ($books as $category=>$book_listing): ?>
                            <div id="<?= str_replace(' ','-', strtolower($category)); ?>" data-tab-content class="active">
                                <div class="row">
                                    <?php foreach ($book_listing->getRecords() as $book): ?>
                                        <?php if($book instanceof \Mini\Cms\Modules\Modal\RecordCollection): ?>
                                            <?php $book = new \Mini\Modules\contrib\epub\src\Plugin\Book($book->book_identify); ?>
                                            <div class="col-md-3">
                                                <div class="product-item">
                                                    <figure class="product-style">
                                                        <img src="<?= $book->getBookCover(); ?>" alt="<?= $book->getBookTitle(); ?>" class="product-item">
                                                        <button type="button" class="add-to-cart" data-product-tile="<?= $book->id(); ?>">Add to
                                                            Cart</button>
                                                    </figure>
                                                    <figcaption>
                                                        <?php if(!empty($current_user) && $current_user instanceof \Mini\Cms\Modules\CurrentUser\CurrentUser && $current_user->id() == $book->owner()->getUid()): ?>
                                                        <h3><a href="/book/read/<?= $book->id(); ?>" title="<?= $book->getBookTitle(); ?>"><?= $book->getBookTitle(); ?></a></h3>
                                                        <?php else: ?>
                                                            <h3><?= $book->getBookTitle(); ?></h3>
                                                        <?php endif; ?>
                                                        <span><?= $book->getBookAuthor(); ?></span>
                                                        <div class="item-price">$ 40.00</div>
                                                    </figcaption>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div><!--inner-tabs-->

        </div>
    </div>
</section>