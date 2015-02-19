<?php namespace Helstern\Nomsky\Lexer\TextSource;

use Helstern\Nomsky\Text\String\StringReader;
use Helstern\Nomsky\Text\TextSource;

class FileSource implements TextSource
{
    /** @var  \SplFileInfo */
    protected $fileInfo;

    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    public function retrieveText()
    {
        $filePath = $this->fileInfo->getRealPath();
        return file_get_contents($filePath);
    }

    /**
     * @return StringReader|\Helstern\Nomsky\Text\TextReader
     */
    public function createReader()
    {
        $reader = new StringReader($this);
        return $reader;
    }
}
