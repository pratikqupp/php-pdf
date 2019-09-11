<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf;

use App\Infrastructure\Pdf\Document\PdfDocumentInterface;

/**
 * PdfEngineInterface
 */
interface PdfEngineInterface
{
    /**
     * Generates a PDF from the given document object
     *
     * @return resource The pdf document as resource
     * @throws \RuntimeException
     */
    public function generate(PdfDocumentInterface $Pdf);
}
