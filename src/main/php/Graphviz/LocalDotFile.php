<?php namespace Helstern\Nomsky\Graphviz;

class LocalDotFile implements DotFile
{
    /** @var \SplFileInfo */
    protected $fileInfo;

    /** @var \SplFileObject */
    private $fileObject;

    /**
     * @param \SplFileInfo $fileInfo
     */
    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
        $fileObject = $this->openFile();
        $this->fileObject = $fileObject;
    }

    /**
     * @return \SplFileObject
     */
    protected function openFile()
    {
        $fileObject = $this->fileInfo->openFile('w');
        return $fileObject;
    }

    public function close()
    {
        $this->fileObject = null;
    }

    /**
     * @return string
     */
    public function getLineTerminator()
    {
        return "\n";
    }

    public function addLineTerminator()
    {
        $this->assertOpen();

        $lineTerminator = $this->getLineTerminator();
        $bytes = $this->fileObject->fwrite($lineTerminator);

        return $bytes;
    }

    public function addAndTerminateLine($line)
    {
        $this->assertOpen();

        $lineBytes = $this->fileObject->fwrite($line);
        $terminatorBytes = $this->addLineTerminator();

        return $lineBytes + $terminatorBytes;
    }

    public function add($text)
    {
        $this->assertOpen();

        $textBytes = $this->fileObject->fwrite($text);
        return $textBytes;
    }

    protected function assertOpen()
    {
        if (is_null($this->fileObject)) {
            throw new \RuntimeException('file was closed');
        }
    }

    public function __destruct()
    {
        $this->fileObject = null;
    }
}
