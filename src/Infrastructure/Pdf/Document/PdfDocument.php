<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf\Document;

/**
 * Pdf Document DTO
 */
class PdfDocument implements PdfDocumentInterface
{
    /**
     * @var string
     */
    protected $content = '';

    /**
     * Orientation
     *
     * @var string
     */
    protected $orientation = Orientation::PORTRAIT;

    /**
     * Margin
     *
     * @var \App\Infrastructure\Pdf\Margin
     */
    protected $margin;

    /**
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     *
     */
    public function __construct(
        string $content,
        ?string $orientation = null,
        ?Marigin $marigin = null
    ) {
        $this->content = $content;
        $this->margin = $marigin === null ? Margin::create(25, 25, 25, 25) : $marigin;
    }

    /**
     * @return $this
     */
    public function setOrientation(string $orientation)
    {
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * @param string
     *
     * @return $this
     */
    public function setEncoding(string $encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * @param \App\Infrastructure\Pdf\Document\Margin $margin Margin
     *
     * @return $this
     */
    public function setMargin(Margin $margin)
    {
        $this->margin = $margin;

        return $this;
    }

    /**
     * Page Size
     *
     * @return string
     */
    public function pageSize(): string
    {
        return 'A4';
    }

    /**
     * @return string
     */
    public function encoding(): string
    {
        return $this->encoding;
    }

    /**
     * Orientation
     *
     * @return string
     */
    public function orientation(): string
    {
        return $this->orientation;
    }

    /**
     * Document Title
     *
     * @return string
     */
    public function title(): string
    {
        return '';
    }

    /**
     * Margins
     */
    public function margin()
    {
        return [
            'top' => 10,
            'bottom' => 10,
            'left' => 10,
            'right' => 10
        ];
    }

    /**
     * Content
     *
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }
}
