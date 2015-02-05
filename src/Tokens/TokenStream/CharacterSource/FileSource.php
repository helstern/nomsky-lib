<?php namespace Helstern\Nomsky\Tokens\TokenStream\CharacterSource;

use Helstern\Nomsky\Tokens\TokenStream\CharacterSource;

class FileSource implements CharacterSource
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
}
