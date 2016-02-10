<?php namespace Helstern\Nomsky\Text;

class FileSource implements TextSource
{
    /** @var  \SplFileInfo */
    protected $fileInfo;

    /**
     * @param \SplFileInfo $fileInfo
     */
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
     * @return StringReader
     */
    public function createReader()
    {
        $string = $this->retrieveText();
        $reader = new StringReader($string);
        return $reader;
    }
}
