<?php namespace Helstern\Nomsky\Grammars\Ebnf;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens\TokenPatterns;
use Helstern\Nomsky\Lexer\TokenStream\FirstMatchCompositeMatcher;
use Helstern\Nomsky\Lexer\TextSource\FileSource;

use Helstern\Nomsky\Lexer\TokenStreamLexer;
use Helstern\Nomsky\Text\TextSource;

use Helstern\Nomsky\Tokens\EofTokenDefinition;
use Helstern\Nomsky\Tokens\TokenMatch\AnchoredPcreMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenStream\TextReaderAdapter;

class IsoEbnfLexerFactory
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
     * @return TextReaderAdapter
     */
    protected function createTokenStream(TextSource $source, array $tokenPatterns)
    {
        $reader = $source->createReader();
        $matcherListAdapter = $this->createFirstMatchListMatcher($tokenPatterns);

        $tokenStream = new TextReaderAdapter($reader, $matcherListAdapter, new EofTokenDefinition);
        return $tokenStream;
    }

    /**
     * @param array|AbstractRegexTokenPattern[] $tokenPatterns
     * @return FirstMatchCompositeMatcher
     */
    protected function createFirstMatchListMatcher(array $tokenPatterns)
    {
        $matchers = [];
        foreach ($tokenPatterns as $pattern) {
            $matchers[] = $this->createPcreMatcher($pattern);
        }

        $listMatcher = new FirstMatchCompositeMatcher($matchers);
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
