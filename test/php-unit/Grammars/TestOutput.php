<?php namespace Helstern\Nomsky\Grammars;

class TestOutput
{
    /** @var string */
    protected $baseDir;

    public function __construct($basePath = null) {
        if (is_null($basePath)) {
            $baseDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output';
        } else {
            $realBasePath = realpath($basePath);
            if (false === $realBasePath) {
                throw new \Exception('Invalid path ' . $basePath);
            }
            if (is_file($realBasePath)) {
                $realBasePath = dirname($realBasePath);
            }

            $baseDir = rtrim($realBasePath, DIRECTORY_SEPARATOR);
        }

        $this->ensurePresent($baseDir);
        $this->makeDirWritable($baseDir);
        $this->baseDir = $baseDir;
    }

    protected function ensurePresent($baseDir)
    {
        $fileInfo = new \SplFileInfo($baseDir);
        if ($fileInfo->isDir()) {
            return ;
        }

        $filePath = $fileInfo->getPathname();
        mkdir($filePath);
    }

    protected function makeDirWritable($baseDir)
    {
        chmod($baseDir, 0777);
    }

    /**
     * @param string $fileName
     * @return \SplFileInfo
     */
    public function createFile($fileName)
    {
        $filePath = $this->getOutputFilePath($fileName);
        file_put_contents($filePath, '');

        $fileInfo = $this->getFileInfo($fileName);
        return $fileInfo;
    }

    /**
     * @param $fileName
     * @return bool
     */
    public function deleteFile($fileName)
    {
        $fileInfo = $this->getFileInfo($fileName);
        $filePath = $fileInfo->getPathname();

        $unlinkResult = unlink($filePath);
        return $unlinkResult;
    }

    /**
     * @param $fileName
     * @return bool
     */
    public function fileExists($fileName)
    {
        $fileInfo = $this->getFileInfo($fileName);
        return $fileInfo->isFile();
    }

    /**
     * @param $fileName
     * @return \SplFileInfo
     */
    public function getFileInfo($fileName)
    {
        $filePath = self::getOutputFilePath($fileName);
        return new \SplFileInfo($filePath);
    }

    public function getOutputFilePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [$this->baseDir, $fileName]);
    }

    public function getContents($fileName)
    {
        $filePath = self::getOutputFilePath($fileName);
        return file_get_contents($filePath);
    }
}
