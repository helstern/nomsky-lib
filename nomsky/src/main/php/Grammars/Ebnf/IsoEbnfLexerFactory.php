<?php namespace Helstern\Nomsky\Grammars\Ebnf;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens\TokenPatterns;
use Helstern\Nomsky\Lexer\LongestMatchWinsStrategy;
use Helstern\Nomsky\Lexer\StandardLexer;
use Helstern\Nomsky\Text\FileSource;

use Helstern\Nomsky\Text\WhitespaceMatcher;

class IsoEbnfLexerFactory
{
    /**
     * @param $filePath
     *
     * @return StandardLexer
     */
    public function fromFile($filePath)
    {
        $fileDescriptor = new \SplFileInfo($filePath);
        $source = new FileSource($fileDescriptor);
        $reader = $source->createReader();
        $tokenMatchers = TokenPatterns::regexPatterns();

        $lexer = new StandardLexer($tokenMatchers, new LongestMatchWinsStrategy(), $reader, new WhitespaceMatcher());
        return $lexer;
    }
}
