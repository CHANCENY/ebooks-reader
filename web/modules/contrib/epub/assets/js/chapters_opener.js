/**
 * Chapters openings.
 */

const chaptersMainTags = document.getElementsByClassName('chapter-main');
const chapterInnerSectionTags = document.getElementsByClassName('navigation-inner-links');
const chapterNoTreeTags = document.getElementsByClassName('inner-chapter-no-tree');
if(chaptersMainTags) {
    Array.from(chaptersMainTags).forEach((chapter_main_tag)=>{
        chapter_main_tag.addEventListener('click',(e)=>openChapter(e, chapter_main_tag));
    });
}

if(chapterInnerSectionTags) {
    Array.from(chapterInnerSectionTags).forEach((inner_section_chapter)=>{
        inner_section_chapter.addEventListener('click',(e)=>{
            scrollToSection(e);
        });
    })
}

if(chapterNoTreeTags) {
    Array.from(chapterNoTreeTags).forEach((chapter_no_tree)=>{
        chapter_no_tree.addEventListener('click',(e)=> openChapter(e, chapter_no_tree));
    })
}

function freshInnerEvents() {
    const inner_content_timer = setInterval(()=>{
        const chapterInnerPagesLinkTags = document.getElementsByClassName('book-inner-page-links');
        if(chapterInnerPagesLinkTags) {
            Array.from(chapterInnerPagesLinkTags).forEach((inner_page_link)=>{
                inner_page_link.addEventListener('click',(e)=> openChapter(e, inner_page_link));
            });
            clearInterval(inner_content_timer);
        }
    }, 1000);
}

function openChapter(event, element) {
    const parent_details = element.parentElement;
    if(parent_details.tagName === 'DETAILS') {
       if(!parent_details.open) {
           chapterFetcher(element.getAttribute('data'),element.getAttribute('section'), parseChapterContent);
       }
    }
    if(element.classList.contains('inner-chapter-no-tree')) {
        chapterFetcher(element.getAttribute('data'),element.getAttribute('section'), parseChapterContent);
    }
    if(element.classList.contains('book-inner-page-links')) {
        chapterFetcher(element.getAttribute('data'), element.getAttribute('section'), parseChapterContent);
    }
    screenAdjust();
}

function chapterFetcher(file,section = null, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', SYSTEM_CONFIG.links.chapter_loader + BOOKID + '?file_name=' + file, true);
    xhr.onload = function () {
        if(this.status === 200) {
            let content = null;
            try {
                content = JSON.parse(this.responseText).content;
            } catch (e) {
                console.error('Error parsing JSON:',e.message);
                callback(null,section);

            }
            callback(content,section);
        } else {
            console.error('XHR request failed with status:', this.status);
            callback(null,section); // Invoke the callback with null or handle error accordingly
        }
    };
    xhr.onerror = function () {
        console.error('XHR request encountered an error.');
        callback(null,section); // Invoke the callback with null or handle error accordingly
    };
    xhr.send();
}

function parseChapterContent(content, section=null) {
    if(BOOK_WRAPPER) {
        const div = document.createElement('div');
        div.innerHTML = content;
        BOOK_WRAPPER.innerHTML = '';
        BOOK_WRAPPER.appendChild(div);
        freshInnerEvents();

        setTimeout(()=>{
            if(section)
                forceScrollToSection(section);
        },1000)
    }
}