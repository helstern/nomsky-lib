<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Lexer\TokenStream\LongestMatchCompositeMatcher;
use Helstern\Nomsky\Lexer\TextSource\FileSource;

use Helstern\Nomsky\Text\TextSource;

use Helstern\Nomsky\Tokens\TokenMatch\AnchoredPcreMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenStream\TextReaderTokenStream;

class NomskyTokenStreamLexerFactory
{
    /**
     * @param $filePath
     * @return TokenStreamLexer
     */
    public function fromFile($filePath)
    {
        $fileDescriptor = new \SplFileInfo($filePath);

        $source = new FileSource($fileDescriptor);
        $tokenPatterns = NomskyTokenPatterns::regexPatterns();
        $tokenStream = $this->createTokenStream($source, $tokenPatterns);

        $lexer = new TokenStreamLexer($tokenStream);

        return $lexer;
    }

    /**
     * @param TextSource $source
     * @param array|AbstractRegexTokenPattern[] $tokenPatterns
     * @return TextReaderTokenStream
     */
    protected function createTokenStream(TextSource $source, array $tokenPatterns)
    {
        $reader = $source->createReader();
        $matcherListAdapter = $this->createLongestMatchListMatcher($tokenPatterns);

        $tokenStream = new TextReaderTokenStream($reader, $matcherListAdapter);
        return $tokenStream;
    }

    /**
     * @param array|AbstractRegexTokenPattern[] $tokenPatterns
     * @return LongestMatchCompositeMatcher
     */
    protected function createLongestMatchListMatcher(array $tokenPatterns)
    {
        $matchers = [];
        foreach ($tokenPatterns as $pattern) {
            $matchers[] = $this->createPcreMatcher($pattern);
        }

        $listMatcher = new LongestMatchCompositeMatcher($matchers);
        return $listMatcher;
    }

    /**
     * @param AbstractRegexTokenPattern $pattern
     * @return AnchoredPcreMatcher
     */
    protected function createPcreMatcher(AbstractRegexTokenPattern $pattern)
    {
        return new AnchoredPcreMatcher($pattern);
    }
}
