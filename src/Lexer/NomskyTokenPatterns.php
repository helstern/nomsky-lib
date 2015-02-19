<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Tokens\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexTokenPattern;

class NomskyTokenPatterns
{
    /**
     * @return array|RegexTokenPattern[]
     */
    static public function regexPatterns()
    {
        $regexBuilder = new RegexBuilder();
        $tokens = new NomskyTokenTypeEnum();

        $tokenPatterns = array($tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_CONCATENATE, ','));

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_DEFINITION_LIST_START,
            (string) $regexBuilder->alternatives('=', ':==')
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_DEFINITION_SEPARATOR, '|');

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_START_REPEAT, '{');
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_END_REPEAT, '}');

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_START_OPTION, '[');
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_END_OPTION, ']');

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_START_GROUP, '(');
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_END_GROUP, ')');

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_TERMINATOR, '.');

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE, "'");
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE, '"');

        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[:punct:]');
        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_CHARACTER_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $singleCharacter->implode()->group()->delimit("'"))
                ->add((string) $singleCharacter->implode()->group()->delimit('"'))
        );

        $charactersAndSpaces = $singleCharacter->copy()->add('[:space:]')->groupEach();
        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_STRING_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $charactersAndSpaces->implode()->group()->delimit("'"))
                ->add((string) $charactersAndSpaces->implode()->group()->delimit('"'))
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_CHARACTER_RANGE,
            (string) $regexBuilder->sequence()
                ->add((string) $singleCharacter->implode()->group())
                ->add('..')
                ->add( (string) $singleCharacter->implode()->group())
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_IDENTIFIER,
            (string) $regexBuilder->sequence()
                ->add('[aA-zZ]')
                ->add(
                    (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->repeat()
                )
        );

        return $tokenPatterns;
    }
}
