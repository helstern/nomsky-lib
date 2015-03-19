<?php namespace Helstern\Nomsky\Lexer;

class TestResources
{
    /**
     * @param $fileName
     * @return \SplFileInfo
     */
    static public function getFileObject($fileName)
    {
        $filePath = self::getResourceFilePath($fileName);
        return new \SplFileInfo($filePath);
    }

    static public function getResourceFilePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'resources', $fileName]);
    }

    static public function getContents($fileName)
    {
        $filePath = self::getResourceFilePath($fileName);
        return file_get_contents($filePath);
    }

}
