<?php namespace Helstern\Nomsky\Tokens\TokenPattern;

use Helstern\Nomsky\Tokens\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenTypeEnum;

class RegexPatterns
{
    /**
     * @return array|RegexTokenPattern[]
     */
    static public function nomskyPatterns()
    {
        $regexBuilder = new RegexBuilder();
        $tokens = new TokenTypeEnum();

        $tokenPatterns = array($tokens->buildRegexPattern(TokenTypeEnum::TYPE_CONCATENATE, ','));

        $tokenPatterns[] = $tokens->buildRegexPattern(
            TokenTypeEnum::TYPE_DEFINITION_LIST_START,
            (string) $regexBuilder->alternatives('=', ':==')
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_DEFINITION_SEPARATOR, '|');

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_START_REPEAT, '{');
        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_END_REPEAT, '}');

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_START_OPTION, '[');
        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_END_OPTION, ']');

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_START_GROUP, '(');
        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_END_GROUP, ')');

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_TERMINATOR, '.');

        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_SINGLE_QUOTE, "'");
        $tokenPatterns[] = $tokens->buildRegexPattern(TokenTypeEnum::TYPE_DOUBLE_QUOTE, '"');

        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[:punct:]');
        $tokenPatterns[] = $tokens->buildRegexPattern(
            TokenTypeEnum::TYPE_CHARACTER_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $singleCharacter->implode()->group()->delimit("'"))
                ->add((string) $singleCharacter->implode()->group()->delimit('"'))
        );

        $charactersAndSpaces = $singleCharacter->copy()->add('[:space:]')->groupEach();
        $tokenPatterns[] = $tokens->buildRegexPattern(
            TokenTypeEnum::TYPE_STRING_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $charactersAndSpaces->implode()->group()->delimit("'"))
                ->add((string) $charactersAndSpaces->implode()->group()->delimit('"'))
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            TokenTypeEnum::TYPE_CHARACTER_RANGE,
            (string) $regexBuilder->sequence()
                ->add((string) $singleCharacter->implode()->group())
                ->add('..')
                ->add( (string) $singleCharacter->implode()->group())
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            TokenTypeEnum::TYPE_IDENTIFIER,
            (string) $regexBuilder->sequence()
                ->add('[aA-zZ]')
                ->add(
                    (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->repeat()
                )
        );

        return $tokenPatterns;
    }
}
