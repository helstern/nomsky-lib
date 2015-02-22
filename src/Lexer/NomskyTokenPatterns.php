<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexTokenPattern;

class NomskyTokenPatterns
{
    /** @var RegexBuilder  */
    protected $regexBuilder;

    /**
     * @return array|RegexTokenPattern[]
     */
    static public function regexPatterns()
    {
        $regexBuilder = new RegexBuilder();
        $instance = new self($regexBuilder);

        $tokens = new NomskyTokenTypeEnum();

        $tokenPatterns = [$tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_CONCATENATE, ',')];

        $tokenPatterns[] = $instance->buildDefinitionListStartPattern($tokens);

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_DEFINITION_SEPARATOR,
            $regexBuilder->pattern('|')->quote()->build()
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_START_REPEAT, '{');
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_END_REPEAT, '}');

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_START_OPTION,
            $regexBuilder->pattern('[')->quote()->build()
        );
        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_END_OPTION,
            $regexBuilder->pattern(']')->quote()->build()
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_START_GROUP,
            $regexBuilder->pattern('(')->quote()->build()
        );
        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_END_GROUP,
            $regexBuilder->pattern(')')->quote()->build()
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_TERMINATOR,
            $regexBuilder->pattern('.')->quote()->build()
        );

        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE, "'");
        $tokenPatterns[] = $tokens->buildRegexPattern(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE, '"');

        $tokenPatterns[] = $instance->buildCharacterLiteralPattern($tokens);

        $tokenPatterns[] = $instance->buildStringLiteralPattern($tokens);

        $tokenPatterns[] = $instance->buildCharacterRangePattern($tokens);

        $tokenPatterns[] = $instance->buildIdentifierPattern($tokens);

        return $tokenPatterns;
    }

    public function __construct(RegexBuilder $regexBuilder)
    {
        $this->regexBuilder = $regexBuilder;
    }

    /**
     * @return \Helstern\Nomsky\RegExBuilder\RegexAlternativesBuilder
     */
    public function createSingleCharacterRegex()
    {
        $regexBuilder = $this->regexBuilder;
        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[[:punct:]]');

        return $singleCharacter;
    }

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @return RegexTokenPattern
     */
    public function buildDefinitionListStartPattern(NomskyTokenTypeEnum $tokens)
    {
        $regexBuilder = $this->regexBuilder;

        $tokenPattern = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_DEFINITION_LIST_START,
            (string) $regexBuilder->alternatives('=', ':==')
        );

        return $tokenPattern;
    }

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @return RegexTokenPattern
     */
    public function buildCharacterLiteralPattern(NomskyTokenTypeEnum $tokens)
    {
        $regexBuilder = $this->regexBuilder;

        $singleCharacter = $this->createSingleCharacterRegex();
        $tokenPattern = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_CHARACTER_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $singleCharacter->implode()->group()->delimit("'"))
                ->add((string) $singleCharacter->implode()->group()->delimit('"'))
        );

        return $tokenPattern;
    }

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @return RegexTokenPattern
     */
    public function buildStringLiteralPattern(NomskyTokenTypeEnum $tokens)
    {
        $regexBuilder = $this->regexBuilder;

        $singleCharacter = $this->createSingleCharacterRegex();
        $charactersAndSpaces = $singleCharacter->copy()->add('[[:space:]]')->groupEach();

        $tokenPattern = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_STRING_LITERAL,
            (string) $regexBuilder->alternatives()
                ->add((string) $charactersAndSpaces->implode()->group()->delimit("'"))
                ->add((string) $charactersAndSpaces->implode()->group()->delimit('"'))
        );
        return $tokenPattern;
    }

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @return RegexTokenPattern
     */
    public function buildCharacterRangePattern(NomskyTokenTypeEnum $tokens)
    {
        $regexBuilder = $this->regexBuilder;

        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[[:punct:]]');

        $pattern = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_CHARACTER_RANGE,
            (string) $regexBuilder->sequence()
                ->add((string) $singleCharacter->implode()->group())
                ->add($regexBuilder->pattern('..')->quote()->build())
                ->add( (string) $singleCharacter->implode()->group())
        );

        return $pattern;
    }

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @return RegexTokenPattern
     */
    public function buildIdentifierPattern(NomskyTokenTypeEnum $tokens)
    {
        $regexBuilder = $this->regexBuilder;

        $tokenPattern = $tokens->buildRegexPattern(
            NomskyTokenTypeEnum::TYPE_IDENTIFIER,
            (string) $regexBuilder->sequence()
                ->add('[aA-zZ]')
                ->add(
                    (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->repeat()
                )
        );

        return $tokenPattern;
    }
}
