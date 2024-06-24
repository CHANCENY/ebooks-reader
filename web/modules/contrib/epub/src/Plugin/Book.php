<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

use DOMDocument;
use DOMException;
use Mini\Cms\Controller\Request;
use Mini\Cms\Entities\User;
use Mini\Cms\Modules\Extensions\ModuleHandler\ModuleHandler;
use Mini\Cms\Modules\Modal\RecordCollection;
use Mini\Cms\Modules\Modal\RecordCollections;
use Mini\Cms\Modules\Streams\MiniWrapper;
use Mini\Cms\Theme\FileLoader;
use Mini\Modules\contrib\epub\src\Modal\BookManifestModal;
use Mini\Modules\contrib\epub\src\Modal\BookMetaDataModal;
use Mini\Modules\contrib\epub\src\Modal\BookSpineModal;
use Mini\Modules\contrib\epub\src\Modal\BookTocsModal;

/**
 * @class Book will be used only in reading book information.
 */
readonly class Book
{
    /**
     * BookMeta modal response for book meta data.
     * @var BookMetaDataModal
     */
    private BookMetaDataModal $meta_modal;

    /**
     * BookTocs modal is response for tocs of book.
     * @var BookTocsModal
     */
    private BookTocsModal $tocs_modal;

    /**
     * BookManifest modal will be used to get manifests.
     * @var BookManifestModal
     */
    private BookManifestModal $manifest_modal;

    /**
     * BookSpine modal will be used to get the spine info of a book
     * @var BookSpineModal
     */
    private BookSpineModal $spine_modal;

    /**
     * Construct for a book object.
     * @param string $book_identify Book identify is book id.
     */
    public function __construct(private string $book_identify)
    {
        $this->spine_modal = new BookSpineModal();
        $this->tocs_modal = new BookTocsModal();
        $this->manifest_modal = new BookManifestModal();
        $this->meta_modal = new BookMetaDataModal();
    }

    /**
     * Book meta data collection.
     * @return RecordCollection|null
     */
    public function getBookMetaData(): RecordCollection|null
    {
        return $this->meta_modal->get($this->book_identify, 'book_identify')->getAt(0);
    }

    /**
     * Book manifest collection.
     * @return RecordCollections
     */
    public function getManifestMetaData(): RecordCollections
    {
        return $this->manifest_modal->get($this->book_identify, 'book_identify');
    }

    /**
     * Book tocs collections.
     * @return RecordCollections
     */
    public function getTocsMetaData(): RecordCollections
    {
        return $this->tocs_modal->get($this->book_identify, 'book_identify');
    }

    /**
     * Book spine collections.
     * @return RecordCollections
     */
    public function getSpineMetaData(): RecordCollections
    {
        return $this->spine_modal->get($this->book_identify, 'book_identify');
    }

    /**
     * Find book source directory if not found null will be turned.
     * @return string|null
     */
    public function getBookRootPath(): string|null
    {
       $source = "private://books/ebook-$this->book_identify";
       if (file_exists($source)) {
           return $source;
       }
       return null;
    }

    /**
     * Finding index.xhtml or html file and return its path.
     * @return string|null
     */
    public function getBookIndex(): string|null
    {
        $manifests_collections = $this->getManifestMetaData()->getRecords();
        $first_file = null;
        foreach ($manifests_collections as $manifests_collection) {
            if($manifests_collection instanceof RecordCollection) {
                if($manifests_collection->manifest_name === 'cover') {
                    $first_file = $manifests_collection->manifest_href;
                    break;
                }
                if((int) $manifests_collection->book_manifest_id === 1) {
                    $first_file = $manifests_collection->manifest_href;
                    break;
                }
            }
        }

        $file = (new FileLoader($this->getBookRootPath()))->findFiles($first_file);
        if(!empty($file)) {
            return reset($file);
        }
        return null;
    }

    /**
     * Process int book content inorder to work well in browser.
     * @param string $file_book_file
     * @return string|null
     * @throws DOMException
     */
    public function bookContent(string $file_book_file): ?string
    {
        if (!file_exists($file_book_file)) {
            return null;
        }

        $request = Request::createFromGlobals();
        $home_domain = trim($request->getSchemeAndHttpHost(),'/');

        $book_content = file_get_contents($file_book_file);

        // Load the XHTML content
        $dom = new DOMDocument();
        $dom->loadXML($book_content);

        // Find all <img> tags
        $imgTags = $dom->getElementsByTagName('img');
        $anchorTags = $dom->getElementsByTagName('a');

        foreach ($imgTags as $index => $imgTag) {
            // Change the src attribute
            $image_path = $home_domain . '/book-images?book=' . $this->book_identify . '&img=' . $imgTag->getAttribute('src');
            $imgTag->setAttribute('src', $image_path);
        }

        foreach ($anchorTags as $index => $anchorTag) {
            $link_href = $anchorTag->getAttribute('href');
            if(!str_starts_with($link_href, 'http://') && !str_starts_with($link_href, 'https://')) {
                $class = $anchorTag->getAttribute('class');
                if(trim($class) !== 'navigation-inner-links' && trim($class) !== 'inner-chapter-no-tree') {
                    if(strpos($link_href, '#')) {
                        $lists = explode('#', $link_href);
                        $anchorTag->setAttribute('data', $lists[0]);
                        $anchorTag->setAttribute('section',end($lists));
                        $anchorTag->setAttribute('href', '#');
                    }
                    else {
                        $anchorTag->setAttribute('data', $link_href);
                        $anchorTag->setAttribute('href', '#');
                    }
                    $anchorTag->setAttribute('class', 'book-inner-page-links');
                }
            }
        }

        // TODO: handle link tags.

        // Extract the content within the <body> tag
        $body = $dom->getElementsByTagName('body')->item(0);
        $innerBodyContent = '';

        foreach ($body->childNodes as $child) {
            $innerBodyContent .= $dom->saveHTML($child);
        }

        // Output the inner content of <body>
        return $innerBodyContent;
    }

    /**
     * Given file name find complete file path
     * @param string $file_book_file
     * @return string|null
     */
    public function findBookFile(string $file_book_file): ?string
    {
        $files = new FileLoader($this->getBookRootPath());
        $book_file = $files->findFiles($file_book_file);
        if (!empty($book_file)) {
            return reset($book_file);
        }
        return null;
    }

    /**
     * Collecting css files of ebook.
     * @return array
     */
    public function bookStyleSheets(): array
    {
        $manifests_collections = $this->getManifestMetaData()->getRecords();
        $css_files = [];
        foreach ($manifests_collections as $manifests_collection) {
            if($manifests_collection instanceof RecordCollection) {
                if(str_contains($manifests_collection->manifest_type, '/css')) {
                   $css = (new FileLoader($this->getBookRootPath()))->findFiles($manifests_collection->manifest_name);
                   if(!empty($css)) {
                       if(!file_exists($css[0]. '_modified.css')) {
                           $css[0] = $this->cssParse($css[0]);
                       }
                       else {
                           $css[0] = $css[0]. '_modified.css';
                       }
                       $css_files[] = '/'. trim((new MiniWrapper())->getRealPath($css[0]), '/');
                   }
                }
            }
        }
        return $css_files;
    }

    /**
     * Collect the navigation array
     * @param string|null $navigation_template Give file name where navigation html reside. in this module.
     * @return array|string Array of navigations or html.
     */
    public function navigationList(string|null $navigation_template = null):array|string
    {
        $tocs_collections = $this->getTocsMetaData()->getRecords();
        $navigation_list = [];
        foreach ($tocs_collections as $tocs_collection) {
            if($tocs_collection instanceof RecordCollection) {
                $toc_content_src = $tocs_collection->tocs_content_src;
                if(str_contains($toc_content_src, '#')) {
                    $list = explode('#', $toc_content_src);
                    $navigation_list[$list[0]][] = ['href' => $toc_content_src, 'label' => $tocs_collection->tocs_title];
                }
            }
        }
        if(!empty($navigation_template)) {
            $module = new ModuleHandler('epub');
            $source = $module->getPath();
            $navigation_template = (new FileLoader($source))->findFiles($navigation_template);
            if(!empty($navigation_template[0]) && file_exists($navigation_template[0])) {
                extract(['nav' => $navigation_list]);
                ob_start();
                require_once (new MiniWrapper())->getRealPath($navigation_template[0]);
                $navigation_list = ob_get_contents();
                ob_clean();
                ob_flush();
            }
        }
        return $navigation_list;
    }

    /**
     * Loading index first page of a book.
     * @return string|null
     * @throws DOMException
     */
    public function loadFistPageContent(): ?string
    {
        $navigation = $this->navigationList('book_navigation.php');
        $book_content = $this->bookContent($this->getBookIndex());

        $module = new ModuleHandler('epub');
        $source = $module->getPath();
        $content = null;
        $index_path = (new FileLoader($source))->findFiles('index.php');
        if(!empty($index_path[0]) && file_exists($index_path[0])) {
            extract(['navigation' => $navigation, 'book_content' => $book_content, 'book_id' => $this->book_identify, 'title'=>$this->getBookTitle()]);
            ob_start();
            require_once (new MiniWrapper())->getRealPath($index_path[0]);
            $content = ob_get_contents();
            ob_clean();
            ob_flush();
        }
        return $content;
    }

    /**
     * Book title.
     * @return string|null
     */
    public function getBookTitle(): ?string
    {
        return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->title ?? null;
    }

    /**
     * Book description.
     * @return string|null
     */
    public function getBookDescription(): ?string
    {
        return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->description ?? null;
    }

    /**
     * Book Author
     * @return string|null
     */
    public function getBookAuthor(): ?string
    {
        return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->creator ?? null;
    }

    /**
     * Book publisher
     * @return string|null
     */
    public function getBookPublisher(): ?string
    {
        return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->publisher ?? null;
    }

    /**
     * Rights to own the book.
     * @return string|null
     */
    public function getBookCopyright(): ?string
    {
        return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->rights ?? null;
    }

    /**
     * Publication date.
     * @return string|null
     */
    public function getPublicationDate(): ?string
    {
       return $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->date ?? null;
    }

    private function lookForCoverImage(): array
    {
        $manifests_collections = $this->getManifestMetaData()->getRecords();
        $covers = [];
        foreach ($manifests_collections as $manifests_collection) {
            if($manifests_collection instanceof RecordCollection) {
                if(strpos(strtolower($manifests_collection->manifest_name),'cover')) {
                    $list = explode('/',$manifests_collection->manifest_href);
                    $covers[] = end($list);
                }
            }
        }
        return $covers;
    }

    /**
     * Book cover image.
     * @return string|null
     */
    public function getBookCover(): ?string
    {
        $cover = $this->meta_modal->get($this->book_identify,'book_identify')->getAt(0)?->cover ?? null;
        if(!empty($cover)) {
            $cover = (new FileLoader($this->getBookRootPath()))->findFiles($cover)[0] ?? null;
            if(!empty($cover)) {
                $cover = "/". trim((new MiniWrapper())->getRealPath($cover), '/');
            }
            else {
                $covers = $this->lookForCoverImage();
                if(!empty($covers)) {
                    foreach($covers as $k=>$c) {
                        $cover = (new FileLoader($this->getBookRootPath()))->findFiles($c)[0] ?? null;
                        if(!empty($cover)) {
                            $cover = "/". trim((new MiniWrapper())->getRealPath($cover), '/');
                            break;
                        }
                    }
                }
            }
        }

        if(!empty($cover)) {
            return $cover;
        }

        $module = new ModuleHandler('epub');
        $path = $module->getPath();
        if(!empty($path)) {
            $cover = (new FileLoader($path))->findFiles('default_book_cover.png')[0] ?? null;
            if(!empty($cover)) {
                $cover = "/". trim((new MiniWrapper())->getRealPath($cover), '/');
            }
        }
        return $cover;
    }

    private function cssParse(string $file): string
    {
       // Step 1: Read the CSS file content
        $cssContent = file_get_contents($file);
        if ($cssContent === false) {
           return $file;
        }

        // Regex pattern to match /* ... */
        $pattern = '/\/\*.*?\*\//s';

        // Replace the matched pattern with an empty string
        $cleaned_content = preg_replace($pattern, '', $cssContent);

        $list = explode("}", $cleaned_content);
        $modified_css = null;

        foreach($list as $css) {
            $batch = trim($css);
            if(!empty($batch) && !str_starts_with(trim($batch), 'html') && !str_starts_with(trim($batch), 'body') ) {
                $list2 = explode("{", $batch);
                $selectors = trim($list2[0]);
                $modified_css .= ".book-content ".$selectors. "{" .PHP_EOL . trim( end($list2)) . PHP_EOL."}".PHP_EOL;
            }
        }
        $new_file = $file . "_modified.css";
        file_put_contents($new_file, $modified_css);
        return $new_file;
    }

    /**
     * Book identify id.
     * @return string
     */
    public function id(): string
    {
        return $this->book_identify;
    }

    public function owner(): User
    {
        return User::load($this->meta_modal->get($this->book_identify, 'book_identify')->getAt(0)->book_meta_table_uid);
    }
}