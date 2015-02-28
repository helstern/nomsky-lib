<?php namespace Helstern\Nomsky\NomskyLexer;

use Helstern\Nomsky\Lexer\TokenStream\LongestMatchCompositeMatcher;
use Helstern\Nomsky\Lexer\TextSource\FileSource;

use Helstern\Nomsky\Lexer\TokenStreamLexer;
use Helstern\Nomsky\Text\TextSource;

use Helstern\Nomsky\Tokens\TokenMatch\AnchoredPcreMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenStream\TextReaderTokenStream;

class TokenStreamLexerFactory
{
    /**
     * @param $filePath
     * @return TokenStreamLexer
     */
    public function fromFile($filePath)
    {
        $fileDescriptor = new \SplFileInfo($filePath);

        $source = new FileSource($fileDescriptor);
        $tokenPatterns = TokenPatterns::regexPatterns();
        $tokenStream = $this->createTokenStream($source, $tokenPatterns);

        $lexer = new TokenStreamLexer($tokenStream, new EofTokenDefinition);

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

        $tokenStream = new TextReaderTokenStream($reader, $matcherListAdapter, new EofTokenDefinition);
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
