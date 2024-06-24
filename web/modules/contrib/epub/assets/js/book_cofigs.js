const BOOK_WRAPPER = document.getElementById('book-main-content-wrapper');
const BOOK_OUTER_WRAPPER = document.getElementById('book-wrapper');
let BOOKID = null;
let SYSTEM_CONFIG = null;


if(BOOK_WRAPPER && BOOK_OUTER_WRAPPER) {
    BOOKID = BOOK_WRAPPER.getAttribute('book');

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/book/configuration-loader/'+BOOKID, false);
    xhr.onload = function () {

        if(this.status === 200) {
            try{
                SYSTEM_CONFIG = JSON.parse(this.responseText).system;
            }catch (e) {
                const h2 = document.createElement('h2');
                h2.textContent = "Sorry you can proceed with this book please refresh may solve the issue";
                BOOK_OUTER_WRAPPER.innerHTML = '';
                BOOK_OUTER_WRAPPER.appendChild(h2);
            }
        }
        else {
            const h2 = document.createElement('h2');
            h2.textContent = "Sorry you can proceed with this book please refresh may solve the issue";
            BOOK_OUTER_WRAPPER.innerHTML = '';
            BOOK_OUTER_WRAPPER.appendChild(h2);
        }
    }
    xhr.onerror = function () {
        const h2 = document.createElement('h2');
        h2.textContent = "Sorry you can proceed with this book please refresh may solve the issue";
        BOOK_OUTER_WRAPPER.innerHTML = '';
        BOOK_OUTER_WRAPPER.appendChild(h2);
    }
    xhr.send();
}
