<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf;

use RuntimeException;
use App\Infrastructure\Pdf\Document\PdfDocumentInterface;

/**
 * WkHtmlToPdfEngine
 */
class WkHtmlToPdfEngine implements PdfEngineInterface
{
    /**
     * @var \App\Infrastructure\Pdf\Document\PdfDocumentInterface
     */
    protected $document;

    /**
     * Path to the wkhtmltopdf executable binary
     *
     * @var string
     */
    protected $binary = '/usr/bin/wkhtmltopdf';

    /**
     * Windows Binary
     *
     * @var string
     */
    protected $windowsBinary = 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe';

    /**
     * Flag to indicate if the environment is windows
     *
     * @var bool
     */
    protected $isWindowsEnvironment;

    /**
     * Constructor
     *
     * @param \CakePdf\Pdf\CakePdf $Pdf CakePdf instance
     */
    public function __construct()
    {
        $this->isWindowsEnvironment = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($this->isWindowsEnvironment) {
            $this->binary = $this->windowsBinary;
        }
    }

    /**
     * Generates Pdf from html
     *
     * @return string Raw PDF data
     * @throws \Cake\Core\Exception\Exception
     * @throws \Exception If no output is generated to stdout by wkhtmltopdf.
     */
    public function generate(PdfDocumentInterface $pdf)
    {
        $this->document = $pdf;

        $command = $this->getCommand();
        $content = $this->exec($command, $this->document->content());

        if (!empty($content['stdout'])) {
            return $content['stdout'];
        }

        if (!empty($content['stderr'])) {
            throw new RuntimeException(
                sprintf(
                    'System error "%s" when executing command "%s". ' .
                    'Try using the binary provided on http://wkhtmltopdf.org/downloads.html',
                    $content['stderr'],
                    $command
                )
            );
        }

        throw new RuntimeException('WKHTMLTOPDF didn\'t return any data');
    }

    /**
     * Execute the WkHtmlToPdf commands for rendering pdfs
     *
     * @param string $cmd the command to execute
     * @param string $input Html to pass to wkhtmltopdf
     * @return array the result of running the command to generate the pdf
     */
    protected function exec($cmd, $input)
    {
        $result = [
            'stdout' => '',
            'stderr' => '',
            'return' => ''
        ];

        // $cwd = $this->getConfig('cwd');
        $cwd = null;

        $proc = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, $cwd);
        fwrite($pipes[0], $input);
        fclose($pipes[0]);

        $result['stdout'] = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $result['stderr'] = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $result['return'] = proc_close($proc);

        return $result;
    }

    /**
     * Get the command to render a pdf
     *
     * @return string the command for generating the pdf
     * @throws \Cake\Core\Exception\Exception
     */
    protected function getCommand()
    {
        if (!is_executable($this->binary)) {
            throw new RuntimeException(sprintf(
                'wkhtmltopdf binary is not found or not executable: %s',
                $this->binary
            ));
        }

        $options = [
            'quiet' => true,
            'print-media-type' => true,
            'orientation' => $this->document->orientation(),
            'page-size' => $this->document->pageSize(),
            'encoding' => $this->document->encoding(),
            'title' => $this->document->title(),
            //'javascript-delay' => $this->document->delay(),
            //'window-status' => $this->document->windowStatus(),
        ];

        $margin = $this->document->margin();
        foreach ($margin as $key => $value) {
            if ($value !== null) {
                $options['margin-' . $key] = $value . 'mm';
            }
        }
        //$options = array_merge($options, (array)$this->getConfig('options'));

        if ($this->isWindowsEnvironment) {
            $command = '"' . $this->binary . '"';
        } else {
            $command = $this->binary;
        }

        foreach ($options as $key => $value) {
            if (empty($value)) {
                continue;
            } elseif (is_array($value)) {
                foreach ($value as $k => $v) {
                    $command .= sprintf(' --%s %s %s', $key, escapeshellarg($k), escapeshellarg($v));
                }
            } elseif ($value === true) {
                $command .= ' --' . $key;
            } else {
                $command .= sprintf(' --%s %s', $key, escapeshellarg($value));
            }
        }

        /*
        $footer = $this->document->footer();
        foreach ($footer as $location => $text) {
            if ($text !== null) {
                $command .= " --footer-$location \"" . addslashes($text) . "\"";
            }
        }

        $header = $this->document->header();
        foreach ($header as $location => $text) {
            if ($text !== null) {
                $command .= " --header-$location \"" . addslashes($text) . "\"";
            }
        }
        */
        $command .= " - -";

        if ($this->isWindowsEnvironment) {
            $command = '"' . $command . '"';
        }

        return $command;
    }
}
