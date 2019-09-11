<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf\Document;

/**
 * Pdf Document Interface
 */
interface PdfDocumentInterface
{
    public function encoding(): string;
    public function title(): string;
    public function pageSize(): string;
    public function orientation(): string;
    public function margin();
}
