function newBook() {
    const new_book_dialog = document.getElementById('new-book');
    if(new_book_dialog) {
        new_book_dialog.showModal();
    }
}

function updateBook(element) {
    const formUp = document.getElementById("edit-book");
    if(formUp) {
        const formElem = formUp.querySelector('form');
        if(formElem) {
            const hd = document.createElement('input');
            hd.type = 'hidden';
            hd.name = 'book_identify';
            hd.value = element.getAttribute('book');
            formElem.appendChild(hd);
            formUp.showModal();
        }
    }
}

function closeDialog(dialog) {
    dialog.close();
}


const new_book = document.getElementById('new-book-button');
if(new_book) {
    new_book.addEventListener('click',(e)=>{
        newBook();
    })
}

const update_books = document.querySelectorAll(".book-edit");
if(update_books) {
    Array.from(update_books).forEach((item)=>{
        item.addEventListener('click',(e)=>{
            e.preventDefault();
            updateBook(item);
        });
    });
}
