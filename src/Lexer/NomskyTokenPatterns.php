<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexAlternativesTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\RegexStringTokenPattern;

use Helstern\Nomsky\Lexer\NomskySymbolsEnum as Symbols;
use Helstern\Nomsky\Lexer\NomskyTokenTypesEnum as TokenTypes;

class NomskyTokenPatterns
{
    /** @var RegexBuilder  */
    protected $regexBuilder;

    /**
     * @param TokenTypes $tokens
     * @throws \RuntimeException
     * @return array|AbstractRegexTokenPattern[]
     */
    static public function regexPatterns(TokenTypes $tokens = null)
    {
        if (is_null($tokens)) {
            $tokens = new TokenTypes();
        }

        $requiredTokens = array(
            TokenTypes::ENUM_CONCATENATE,
            TokenTypes::ENUM_DEFINITION_LIST_START,
            TokenTypes::ENUM_DEFINITION_SEPARATOR,
            TokenTypes::ENUM_START_REPEAT,
            TokenTypes::ENUM_END_REPEAT,
            TokenTypes::ENUM_START_OPTION,
            TokenTypes::ENUM_END_OPTION,
            TokenTypes::ENUM_START_GROUP,
            TokenTypes::ENUM_END_GROUP,
            TokenTypes::ENUM_TERMINATOR,
            TokenTypes::ENUM_CHARACTER_LITERAL,
            TokenTypes::ENUM_STRING_LITERAL,
            TokenTypes::ENUM_COMMENT_LITERAL,
            TokenTypes::ENUM_CHARACTER_RANGE,
            TokenTypes::ENUM_IDENTIFIER
        );

        foreach ($requiredTokens as $requiredTokenType) {
            if (!$tokens->contains($requiredTokenType)) {
                throw new \RuntimeException('required token missing from enum');
            }
        }

        $regexBuilder = new RegexBuilder();
        $instance = new self($regexBuilder);
        $tokenPatterns = [];

        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_CONCATENATE, Symbols::ENUM_CONCATENATE);

        $tokenPatterns[] = $instance->buildDefinitionListStartPattern(TokenTypes::ENUM_DEFINITION_LIST_START);

        $tokenPatterns[] = $instance->createStringPattern(
            TokenTypes::ENUM_DEFINITION_SEPARATOR,
            $regexBuilder->quoted(Symbols::ENUM_DEFINITION_SEPARATOR)->build()
        );

        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_START_REPEAT, Symbols::ENUM_START_REPEAT);

        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_END_REPEAT, Symbols::ENUM_END_REPEAT);

        $tokenPatterns[] = $instance->createStringPattern(
            TokenTypes::ENUM_START_OPTION,
            (string) $regexBuilder->quoted(Symbols::ENUM_START_OPTION)
        );

        $tokenPatterns[] = $instance->createStringPattern(
            TokenTypes::ENUM_END_OPTION,
            (string) $regexBuilder->quoted(Symbols::ENUM_END_OPTION)
        );

        $tokenPatterns[] = $instance->createStringPattern(
            TokenTypes::ENUM_START_GROUP,
            (string) $regexBuilder->quoted(Symbols::ENUM_START_GROUP)
        );

        $tokenPatterns[] = $instance->createStringPattern(
            TokenTypes::ENUM_END_GROUP,
            (string) $regexBuilder->quoted(Symbols::ENUM_END_GROUP)
        );

        $tokenPatterns[] = $instance->buildTerminatorPattern(TokenTypes::ENUM_TERMINATOR);

//        if ($tokens->contains(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE)) {
//            $tokenPatterns[] = $instance->buildSingleQuotePattern(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE);
//        } else {
//            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE');
//        }
//
//        if ($tokens->contains(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE)) {
//            $tokenPatterns[] = $instance->buildDoubleQuotePattern(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE);
//        } else {
//            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE');
//        }

        $tokenPatterns[] = $instance->buildCharacterLiteralPattern(TokenTypes::ENUM_CHARACTER_LITERAL);

        $tokenPatterns[] = $instance->buildStringLiteralPattern(TokenTypes::ENUM_STRING_LITERAL);

        $tokenPatterns[] = $instance->buildCommentLiteralPattern(TokenTypes::ENUM_COMMENT_LITERAL);

        $tokenPatterns[] = $instance->buildCharacterRangePattern(TokenTypes::ENUM_CHARACTER_RANGE);

        $tokenPatterns[] = $instance->buildIdentifierPattern(TokenTypes::ENUM_IDENTIFIER);

        return $tokenPatterns;
    }

    public function __construct(RegexBuilder $regexBuilder)
    {
        $this->regexBuilder = $regexBuilder;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildDefinitionListStartPattern($tokenType)
    {
        $alternatives = [
            Symbols::ENUM_DEFINE
            , Symbols::ENUM_DEFINE_ALT_ONE
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
    }

//    /**
//     * @param int $tokenType
//     * @return RegexStringTokenPattern
//     */
//    public function buildSingleQuotePattern($tokenType)
//    {
//        $pattern = $this->createStringTokenPattern($tokenType, "'");
//        return $pattern;
//    }
//
//    /**
//     * @param int $tokenType
//     * @return RegexStringTokenPattern
//     */
//    public function buildDoubleQuotePattern($tokenType)
//    {
//        $pattern = $this->createStringTokenPattern($tokenType, '"');
//        return $pattern;
//    }

    //\(\*(?!\*\))?(?:.|\n|\r)*?\*\)

    /**
     * @param $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildTerminatorPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern = (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR)->quote();

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;
    }

    public function buildCommentLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern = (string) $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_START_COMMENT)->quote()
            , (string) $regexBuilder->negativeLookAhead(Symbols::ENUM_END_COMMENT)->lazy()->quote()
            , (string) $regexBuilder->alternatives('.', '\n', '\r')->group()->repeatZeroOrMore()->lazy()
            , (string) $regexBuilder->pattern(Symbols::ENUM_END_COMMENT)->quote()
        );

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;

    }

    /**
     * @param int $tokenType
     * @return RegexAlternativesTokenPattern
     */
    public function buildCharacterLiteralPattern($tokenType)
    {
        $singleCharacter = $this->createSingleCharacterRegex();
        $alternatives = [
            (string) $singleCharacter->group()->delimit(Symbols::ENUM_SINGLE_QUOTE)
            , (string) $singleCharacter->group()->delimit(Symbols::ENUM_DOUBLE_QUOTE)
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexAlternativesTokenPattern
     */
    public function buildStringLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $alternatives = [
            (string) $regexBuilder->sequence(
                Symbols::ENUM_SINGLE_QUOTE,
                (string) $regexBuilder->alternatives("[^']", str_repeat(Symbols::ENUM_SINGLE_QUOTE, 2))
                    ->group()
                    ->repeatOnceOrMore(),
                Symbols::ENUM_SINGLE_QUOTE
            )
            , (string) $regexBuilder->alternatives('[^"]', str_repeat(Symbols::ENUM_DOUBLE_QUOTE, 2))
                ->group()
                ->repeatOnceOrMore()
                ->delimit(Symbols::ENUM_DOUBLE_QUOTE)
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildCharacterRangePattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[[:punct:]]');
        $stringPattern = (string) $regexBuilder->sequence(
            (string) $singleCharacter->group()
            , $regexBuilder->pattern('..')->quote()->build()
            , (string) $singleCharacter->group()
        );

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildIdentifierPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern =  (string) $regexBuilder->sequence()
            ->add('[aA-zZ]')
            ->add(
            //(string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->group()->repeat()->group()
            (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->group()->repeatOnceOrMore()
        );

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;
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
     * @param int $tokenType
     * @param string $stringPattern
     * @throws \RuntimeException
     * @return RegexStringTokenPattern
     */
    protected function createStringPattern($tokenType, $stringPattern)
    {
        $tokenPattern = new RegexStringTokenPattern((int) $tokenType, $stringPattern);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @param array $stringPatterns
     * @throws \RuntimeException
     * @return RegexAlternativesTokenPattern
     */
    protected function createAlternativesTokenPattern($tokenType, array $stringPatterns)
    {
        $tokenPattern = new RegexAlternativesTokenPattern((int) $tokenType, $stringPatterns);
        return $tokenPattern;
    }
}
