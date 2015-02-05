<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Tokens\Text\RegexMatchStream;
use Helstern\Nomsky\Tokens\Text\RegexPatternBuilder;
use Helstern\Nomsky\Tokens\TokenStream\CharacterSource\FileSource;
use Helstern\Nomsky\Tokens\TokenStream\CharacterSource;
use Helstern\Nomsky\Tokens\TokenStream\RegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenStream\RegexTokenPatterns;
use Helstern\Nomsky\Tokens\TokenStream\RegexTokenStream;

class NomskyRegexLexerFactory
{
    /**
     * @param $filePath
     * @return TokenStreamLexer
     */
    public function fromFile($filePath)
    {
        $fileDescriptor = new \SplFileInfo($filePath);
        $source = new FileSource($fileDescriptor);

        $tokenPatterns = RegexTokenPatterns::nomskyPatterns();
        $regexMatchStream = $this->createRegexMatchStream($source, $tokenPatterns);

        $tokenStream = new RegexTokenStream($regexMatchStream, $source);

        return new TokenStreamLexer($tokenStream);
    }

    /**
     * @param CharacterSource $source
     * @param array $tokenPatterns
     * @return RegexMatchStream
     */
    protected function createRegexMatchStream(CharacterSource $source, array $tokenPatterns)
    {
        $regexPattern = $this->createRegexPattern($tokenPatterns);
        $regexMatchStream = new RegexMatchStream($regexPattern, $source->retrieveText());

        return $regexMatchStream;
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
}
