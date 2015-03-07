<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens;

use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexAlternativesTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\RegexStringTokenPattern;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens\SymbolsEnum as Symbols;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfTokens\TokenTypesEnum as TokenTypes;

class TokenPatterns
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
        );

        foreach ($requiredTokens as $requiredTokenType) {
            if (!$tokens->contains($requiredTokenType)) {
                throw new \RuntimeException('required token missing from enum');
            }
        }

        $regexBuilder = new RegexBuilder();
        $instance = new self($regexBuilder);
        //add longer patterns first
        $tokenPatterns = [];
        //longer patterns
        $tokenPatterns[] = $instance->buildStringLiteralPattern(TokenTypes::ENUM_STRING_LITERAL);
        $tokenPatterns[] = $instance->buildIdentifierPattern(TokenTypes::ENUM_STRING_LITERAL);
        $tokenPatterns[] = $instance->buildSpecialSequencePattern(TokenTypes::ENUM_SPECIAL_SEQUENCE);
        $tokenPatterns[] = $instance->buildCommentPattern(TokenTypes::ENUM_COMMENT);
        $tokenPatterns[] = $instance->buildDefinitionListStartPattern(TokenTypes::ENUM_DEFINITION_LIST_START);
        //shorter patterns
        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_CONCATENATE, Symbols::ENUM_CONCATENATE);
        $tokenPatterns[] = $instance->createQuotedPattern(
            TokenTypes::ENUM_DEFINITION_SEPARATOR,
            Symbols::ENUM_DEFINITION_SEPARATOR
        );
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_REPEAT, Symbols::ENUM_START_REPEAT);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_REPEAT, Symbols::ENUM_END_REPEAT);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_OPTION, Symbols::ENUM_START_OPTION);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_OPTION, Symbols::ENUM_END_OPTION);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_GROUP, Symbols::ENUM_START_GROUP);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_GROUP, Symbols::ENUM_END_GROUP);
        $tokenPatterns[] = $instance->buildTerminatorPattern(TokenTypes::ENUM_TERMINATOR);

        return $tokenPatterns;
    }

    /**
     * @param RegexBuilder $regexBuilder
     */
    public function __construct(RegexBuilder $regexBuilder)
    {
        $this->regexBuilder = $regexBuilder;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildCommentPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $emptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_START_COMMENT)->quote()
            , (string) $regexBuilder->pattern('\s')->repeatZeroOrMore()
            , (string) $regexBuilder->pattern(Symbols::ENUM_END_COMMENT)->quote()
        );

        $nonEmptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_START_COMMENT)->quote()
            , (string) $regexBuilder->negativeLookAhead(Symbols::ENUM_END_COMMENT)->quote()
            , (string) $regexBuilder->alternatives('.', '\n', '\r')->group()->repeatZeroOrMore()->lazy()
            , (string) $regexBuilder->pattern(Symbols::ENUM_END_COMMENT)->quote()
        );

        $stringPattern = (string) $regexBuilder->alternatives(
            (string) $emptyCommentPattern
            , (string) $nonEmptyCommentPattern
        )->group();

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
                (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]', '[-]')->group()->repeatOnceOrMore()
            );

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildCharLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $pattern = $regexBuilder->sequence(
            Symbols::ENUM_SINGLE_QUOTE,
            (string) $regexBuilder->alternatives(
                '\p{L}|\s',
                str_repeat(Symbols::ENUM_SINGLE_QUOTE, 2)
            )
            ->group(),
            Symbols::ENUM_SINGLE_QUOTE
        );

        $tokenPattern = $this->createStringPattern($tokenType, (string) $pattern);
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
    public function buildSpecialSequencePattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $emptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->pattern('\s')->repeatZeroOrMore()
            , (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
        );

        $nonEmptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->negativeLookAhead(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
        );

        $stringPattern = (string) $regexBuilder->alternatives(
            (string) $emptyCommentPattern
            , (string) $nonEmptyCommentPattern
        )->group();

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;

    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildDecimalDigitPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterRange('0', '9');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildLetterPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterSet('\p{L}');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
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


    //\(\*(?!\*\))?(?:.|\n|\r)*?\*\)

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildTerminatorPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $alternatives = [
            (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR)->quote()
            , (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR_ALT_ONE)->quote()
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
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
     * @param string $unquotedStringPattern
     * @throws \RuntimeException
     * @return RegexStringTokenPattern
     */
    protected function createQuotedPattern($tokenType, $unquotedStringPattern)
    {
        $regexBuilder = $this->regexBuilder;
        $quotedPattern = (string) $regexBuilder->pattern($unquotedStringPattern)->quote();

        $tokenPattern = new RegexStringTokenPattern((int) $tokenType, $quotedPattern);
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
