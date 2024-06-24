
function screenAdjust() {
    // Get the element with the specified ID
    const bookMainContentWrapper = document.getElementById('book-main-content-wrapper');
    const wrapperHeight = window.innerHeight;
    bookMainContentWrapper.setAttribute('style',"overflow-x: auto !important; padding-left: 21px; padding-right: 21px;");
    bookMainContentWrapper.style.height = `${wrapperHeight}px`;

    const bookNavigation = document.getElementById('book-navigation');
    bookNavigation.style.height = `${wrapperHeight}px`;
    bookNavigation.style.overflowX = 'auto';
}

screenAdjust();

function scrollToSection(event) {
    event.preventDefault();
    const targetId = event.target.getAttribute('href').substring(1);
    const targetElement = document.getElementById(targetId);

    // Scroll the book-main-content-wrapper to the target section
    document.getElementById('book-main-content-wrapper').scrollTo({
        top: targetElement.offsetTop,
        behavior: 'smooth' // Optional: smooth scrolling animation
    });
}

function forceScrollToSection(section_id) {
    const targetElement = document.getElementById(section_id);

    if(targetElement) {
        // Scroll the book-main-content-wrapper to the target section
        document.getElementById('book-main-content-wrapper').scrollTo({
            top: targetElement.offsetTop,
            behavior: 'smooth' // Optional: smooth scrolling animation
        });
    }
}

function openFullscreen() {
    if (BOOK_OUTER_WRAPPER.requestFullscreen) {
        BOOK_OUTER_WRAPPER.requestFullscreen();
    } else if (BOOK_OUTER_WRAPPER.webkitRequestFullscreen) { /* Safari */
        BOOK_OUTER_WRAPPER.webkitRequestFullscreen();
    } else if (BOOK_OUTER_WRAPPER.msRequestFullscreen) { /* IE11 */
        BOOK_OUTER_WRAPPER.msRequestFullscreen();
    }
}

function closeFullscreen() {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else if (document.webkitFullscreenElement) { /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msFullscreenElement) { /* IE11 */
        document.msExitFullscreen();
    }
}


const zoomer = document.getElementById('zoomer');
if(zoomer) {
    zoomer.addEventListener('click',()=>{
       if(!zoomer.getAttribute('zoomed')) {
           openFullscreen();
           zoomer.setAttribute('zoomed', '1');
           zoomer.textContent = 'Close Fullscreen';
       }
       else {
           closeFullscreen();
           zoomer.removeAttribute('zoomed');
           zoomer.textContent = 'Enter Fullscreen';
       }
    });
}