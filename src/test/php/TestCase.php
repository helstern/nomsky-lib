<?php namespace Helstern\Nomsky;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
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
        $resourcesDir = realpath(__DIR__ . '/../resources');
        if (empty($resourcesDir)) {
            throw new \Exception('resource dir path is empty');
        }
        return implode(DIRECTORY_SEPARATOR, [$resourcesDir, $fileName]);
    }

    public function getContents($fileName)
    {
        $filePath = self::getResourceFilePath($fileName);
        return file_get_contents($filePath);
    }
}
