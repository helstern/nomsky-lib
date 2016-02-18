<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnf;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\TokenPatterns;
use Helstern\Nomsky\Lexer\LongestMatchWinsStrategy;
use Helstern\Nomsky\Lexer\StandardLexer;
use Helstern\Nomsky\Text\FileSource;

use Helstern\Nomsky\Text\WhitespaceMatcher;

class LexerFactory
{
    /**
     * @param string $filePath
     *
     * @return StandardLexer
     * @throws \InvalidArgumentException
     */
    public function fromFile($filePath)
    {
        $fileDescriptor = new \SplFileInfo($filePath);
        if (! $fileDescriptor->isFile()) {
            throw new \InvalidArgumentException('file not found at: ' . $filePath);
        }

        $source = new FileSource($fileDescriptor);
        $reader = $source->createReader();
        $tokenMatchers = TokenPatterns::regexPatterns();

        $lexer = new StandardLexer($tokenMatchers, new WhitespaceMatcher(), new LongestMatchWinsStrategy(), $reader);
        return $lexer;
    }
}
