<div class="book-content container-fluid mt-lg-4" id="book-wrapper">
    <div class="row book-row-wrapper ms-2">
        <?= $navigation ?? null ?>
        <div class="col-md-8" id="book-main-content-wrapper" book="<?= $book_id ?? null; ?>">
            <?= $book_content ?? null ?>
        </div>
    </div>
    <div class="zoom-controls">
        <a href="#" class="fullscreen-link" id="zoomer">Enter Fullscreen</a>
    </div>
</div>
