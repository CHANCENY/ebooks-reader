<div class="container-fluid mt-lg-5">
    <div class="row">
        <div class="col float-start">
            <button id="new-book-button" class="btn">New Ebook</button>
        </div>
    </div>
    <div class="row">
        <table class="datatable table-stripped">
            <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Category</th>
                <th>Library</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
              <?php if(!empty($books) && $books instanceof \Mini\Cms\Modules\Modal\RecordCollections): ?>

                <?php foreach ($books->getRecords() as $book): ?>
                  <?php $book = new \Mini\Modules\contrib\epub\src\Plugin\Book($book->book_identify); ?>
                  <tr>
                      <td><?= $book->getBookTitle() ?></td>
                      <td><?= $book->getBookAuthor() ?></td>
                      <td><?= (new \DateTime($book->getPublicationDate() ?? 'now'))->format('d F, Y'); ?></td>
                      <td><?= 'None' ?></td>
                      <td><?= 'None' ?></td>
                      <td><a href="#" class="book-edit" book="<?= $book->id(); ?>">Edit</a></td>
                  </tr>
                <?php endforeach; ?>

              <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div>
    <dialog id="new-book" style="border: 1px;">
        <div class="row">
            <a onclick="closeDialog(this.parentElement.parentElement)">close</a>
            <div class="col-md-12">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="ebook" class="for">Ebook Files:</label>
                        <input type="file" max="4" name="ebook[]" id="ebook" multiple class="form-control">
                    </div>
                    <div class="form">
                        <input type="submit" name="new_book" value="Submit New Book" class="btn btn-secondary">
                    </div>
                </form>
            </div>
        </div>
    </dialog>
    <dialog id="edit-book" style="border: 1px;"><a onclick="closeDialog(this.parentElement.parentElement)">close</a>
        <form method="post" enctype="multipart/form-data" class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="for">Title</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="author" class="for">Author</label>
                        <input type="text" name="creator" id="author" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cover" class="for">Cover</label>
                        <input type="file" name="cover" id="cover" class="form-control">
                        <span>allowed (.jpeg, .jpg, .png)</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="version" class="for">Version</label>
                        <input type="text" name="version" id="version" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="publisher" class="for">Publisher</label>
                        <input type="text" name="publisher" id="publisher" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="rights" class="for">Rights</label>
                        <input type="file" accept="text/plain" name="rights" id="rights" class="form-control">
                        <span>allowed (.txt)</span>
                    </div>
                    <div class="form-group">
                        <label for="description" class="for">Description</label>
                        <textarea type="text" name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" name="update_book" value="Update Book" class="form-control">
            </div>
        </form>
    </dialog>
</div>
