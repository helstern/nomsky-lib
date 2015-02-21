<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Lexer\TokenStream\LongestMatchCompositeMatcher;
use Helstern\Nomsky\Lexer\TextSource\FileSource;

use Helstern\Nomsky\Text\TextSource;

use Helstern\Nomsky\TextMatch\RegexPatternBuilder;

use Helstern\Nomsky\Tokens\TokenMatch\AnchoredPcreMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\RegexTokenPattern;
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
     * @param array|RegexTokenPattern[] $tokenPatterns
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
     * @param array|RegexTokenPattern[] $tokenPatterns
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
     * @param array|RegexTokenPattern[] $tokenPatterns
     * @return string
     */
    protected function createRegexPattern(array $tokenPatterns)
    {
        $regexBuilder = new RegexPatternBuilder();
        foreach ($tokenPatterns as $tokenPattern) {
            $regexBuilder->addNamedPattern($tokenPattern->getTokenType(), $tokenPattern->getTokenPattern());
        }

        $pattern = $regexBuilder->build();
        return $pattern;
    }

    /**
     * @param RegexTokenPattern $pattern
     * @return AnchoredPcreMatcher
     */
    protected function createPcreMatcher(RegexTokenPattern $pattern)
    {
        return new AnchoredPcreMatcher($pattern);
    }
}
