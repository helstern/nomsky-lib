<?php namespace Helstern\Nomsky\Parsers;

class TestResources
{
    /** @var string */
    protected $baseDir;

    public function __construct($basePath = null) {
        if (is_null($basePath)) {
            $this->baseDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        } else {
            $realBasePath = realpath($basePath);
            if (false === $realBasePath) {
                throw new \Exception('Invalid path ' . $basePath);
            }
            if (is_file($realBasePath)) {
                $realBasePath = dirname($realBasePath);
            }

            $this->baseDir = rtrim($realBasePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
    }
    /**
     * @param $fileName
     * @return \SplFileInfo
     */
    public function getFileObject($fileName)
    {
        $filePath = self::getResourceFilePath($fileName);
        return new \SplFileInfo($filePath);
    }

    public function getResourceFilePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [$this->baseDir, 'resources', $fileName]);
    }

    public function getContents($fileName)
    {
        $filePath = self::getResourceFilePath($fileName);
        return file_get_contents($filePath);
    }
}
