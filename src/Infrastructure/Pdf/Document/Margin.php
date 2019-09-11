<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf\Document;

/**
 * Document Margins
 */
class Margin
{
    /**
     * @var int
     */
    protected $left = 10;

    /**
     * @var int
     */
    protected $right = 10;

    /**
     * @var int
     */
    protected $top = 10;

    /**
     * @var int
     */
    protected $bottom = 10;

    /**
     * Creates a new margin set
     *
     * @param  int $left   Left margin
     * @param  int $right  Right margin
     * @param  int $top    Top margin
     * @param  int $bottom Bottom margin
     * @return self
     */
    public static function create(
        int $left,
        int $right,
        int $top,
        int $bottom
    ) {
        $self = new self();

        $self->left = $left;
        $self->right = $right;
        $self->top = $top;
        $self->bottom = $bottom;

        return $self;
    }

    /**
     * @return int
     */
    public function left(): int
    {
        return $this->left;
    }

    /**
     * @return int
     */
    public function right(): int
    {
        return $this->right;
    }

    /**
     * @return int
     */
    public function top(): int
    {
        return $this->top;
    }

    /**
     * @return int
     */
    public function bottom(): int
    {
        return $this->bottom;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'left' => $this->left,
            'left' => $this->left,
            'left' => $this->left,
            'left' => $this->left
        ];
    }
}
