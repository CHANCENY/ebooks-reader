<?php

namespace Mini\Modules\contrib\epub\src\Plugin;

use Exception;
use Mini\Cms\Modules\FileSystem\File;
use Mini\Cms\Theme\FileLoader;
use ZipArchive;

/**
 * @class This class is and will be only for extracting epub file into the designated folder, where content of epub can be
 * accessed.
 *
 */

class Extractor
{
    /**
     * Incoming epub file to extractor.
     * @var string
     */
    public string $epub_file;

    /**
     * Flag for error occurred
     * @var bool
     */
    public bool $error_occurred;

    /**
     * The Message of error occurred.
     * @var string
     */
    public string $error_message;

    /**
     * Where will the file be extracted to?
     * @var string
     */
    public string $extract_path;

    /**
     * Current time stamp which will be created for a book.
     * @var string
     */
    public string $book_identify;

    /**
     * Content extracted from content.opt file.
     * @var OPF
     */
    public OPF $opt;

    /**
     * Ebook manifest.
     * @var Manifest
     */
    public Manifest $manifest;

    /**
     * Spine structure of ebook.
     * @var Spine
     */
    public Spine $spine;

    /**
     * Tocs object.
     * @var Tocs
     */
    public Tocs $tocs;

    /**
     * @param string|int $epub_file File path of epub file. Or provide file id in file_managed table.
     * @throws Exception Not file exist exception will be throw if given file is not in a system.
     */
    public function __construct(string|int $epub_file)
    {
        $this->error_occurred = false;
        $this->error_message = '';
        $this->epub_file = '';
        $this->book_identify = time();
        $this->extract_path = 'private://books/ebook-'.$this->book_identify;

        if(is_string($epub_file)) {
            $this->epub_file = $epub_file;
        }
        else {
            $file = File::load($epub_file);
            $this->epub_file = $file->getFilePath();

        }

        if(!file_exists($this->epub_file)) {
            $this->error_occurred = true;
            $this->error_message = 'File not found';
            throw new Exception("Epub file [$epub_file] not found.");
        }
    }

    /**
     * Extracting file contains from epub into folder.
     * @return void
     */
    public function extract(): void
    {
        if(!$this->error_occurred) {

            $zip = new ZipArchive;
            if ($zip->open($this->epub_file) === TRUE) {
                $zip->extractTo($this->extract_path);
                $zip->close();
                $this->error_occurred = false;
            } else {
               $this->error_occurred = true;
               $this->error_message = 'Failed to open zip file.';
            }

        }
    }

    /**
     * Read the opf file to find ebook meta-data.
     * @return void
     */
    public function collectOpfContent(): void
    {
        if(!$this->error_occurred) {

            // Looking for opted file in epub file extracted.
            $content_opf_file = (new FileLoader($this->extract_path))->findFiles('content.opf')[0] ?? null;
            if(empty($content_opf_file)) {
                $this->error_occurred = true;
                $this->error_message = 'content.opf file not found';
            }

            // Extracting other meta-data.
            if($content_opf_file) {
                $opfPath = $content_opf_file;
                $opfContent = file_get_contents($opfPath);
                $opfXml = simplexml_load_string($opfContent);
                $opfXml->registerXPathNamespace('opf', 'http://www.idpf.org/2007/opf');

                $version = (string)$opfXml['version'] ?? '3.0';

                // Extract metadata
                $metadata = $opfXml->metadata;

                // Extracting individual metadata elements
                $title = (string)$metadata->xpath('dc:title')[0] ?? '';
                $creator = (string)$metadata->xpath('dc:creator')[0] ?? '';
                $language = (string)$metadata->xpath('dc:language')[0] ?? '';
                $identifier = (string)$metadata->xpath('dc:identifier')[0] ?? '';
                $publisher = (string)$metadata->xpath('dc:publisher')[0] ?? '';
                $date = (string)$metadata->xpath('dc:date')[0] ?? '';
                $description = (string)$metadata->xpath('dc:description')[0] ?? '';
                $rights = (string)$metadata->xpath('dc:rights')[0] ?? '';

                // Extracting cover image metadata
                $coverMetaNodes = $opfXml->xpath('//opf:metadata/opf:meta[@name="cover"]');
                $coverMeta = !empty($coverMetaNodes) ? $coverMetaNodes[0] : null;
                $coverImageId = $coverMeta ? (string)$coverMeta['content'] : '';
                $this->opt = new OPF(
                    $title,
                    $creator,
                    $language,
                    $identifier,
                    $publisher,
                    $date,
                    $description,
                    $rights,
                    $coverImageId,
                    $version,
                    $this->book_identify
                );
            }

            // Extract manifest data
            if($content_opf_file) {

                // Extract manifest
                $manifest = $opfXml->manifest;

               // Store manifest items in an associative array
                $items = [];
                foreach ($manifest->item as $item) {
                    $attributes = $item->attributes();
                    $items[(string)$attributes['id']] = [
                        'manifest_name' => (string)$attributes['id'],
                        'manifest_href' => (string)$attributes['href'] ?? null,
                        'manifest_type' => (string)$attributes['media-type'] ?? null,
                        'properties' => isset($attributes['properties']) ? (string)$attributes['properties'] : null
                    ];
                }

                $this->manifest = new Manifest($items, $this->book_identify);
            }

            if($content_opf_file) {
                // Extract spine
                $spine = $opfXml->spine;
                $spineItems = [];
                foreach ($spine->itemref as $itemref) {
                    $attributes = $itemref->attributes();
                    $spineItems[] = (string)$attributes['idref'];
                }
                $this->spine = new Spine($spineItems, $this->book_identify);
            }

        }
    }

    /**
     * Extracting toc contents.
     * @return void
     */
    public function collectToc(): void
    {
        if(!$this->error_occurred) {
            $tocPath = (new FileLoader($this->extract_path))->findFiles('toc.ncx')[0] ?? null;
            if(empty($tocPath)) {
                $this->error_occurred = true;
                $this->error_message = 'No toc file found.';
            }

            $tocContent = file_get_contents($tocPath);
            $tocXml = simplexml_load_string($tocContent);
            $tocXml->registerXPathNamespace('ncx', 'http://www.daisy.org/z3986/2005/ncx/');

            // Extract the book title and author
            $docTitle = (string)$tocXml->docTitle->text;
            $docAuthor = (string)$tocXml->docAuthor->text;

            // Extract navigation points
            $navPoints = $tocXml->xpath('//ncx:navPoint');
            $tocs = [];
            foreach ($navPoints as $navPoint) {
                $playOrder = (string)$navPoint['playOrder'];
                $label = (string)$navPoint->navLabel->text;
                $contentSrc = (string)$navPoint->content['src'];
                $tocs[] = [
                    'tocs_title' => $label,
                    'tocs_play_order' => $playOrder,
                    'tocs_content_src' => $contentSrc,
                ];
            }
            $this->tocs = new Tocs($tocs, $this->book_identify);
        }
    }


}