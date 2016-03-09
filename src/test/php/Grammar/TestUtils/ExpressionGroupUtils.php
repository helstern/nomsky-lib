<?php namespace Helstern\Nomsky\Grammar\TestUtils;

use Helstern\Nomsky\Grammar\Expressions\Group;

class ExpressionGroupUtils
{
    /** @var ExpressionUtils  */
    protected $expressionTestUtils;

    public function __construct(ExpressionUtils $expressioTestUtils)
    {
        $this->expressionTestUtils = $expressioTestUtils;
    }

    /**
     * @param array $symbols
     * @return Group
     */
    public function createSequenceFromSymbols(array $symbols)
    {
        $sequence = $this->expressionTestUtils->createConcatenationFromSymbols($symbols);
        return new Group($sequence);
    }

    /**
     * @param array $symbols
     * @return Group
     */
    public function createAlternationFromSymbols(array $symbols)
    {
        $sequence = $this->expressionTestUtils->createChoiceFromSymbols($symbols);
        return new Group($sequence);

    }

    /**
     * @param array $listOfStringSymbols
     * @return Group
     */
    public function createSequenceFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $sequence = $this->expressionTestUtils->createConcatenationFromListOfStringSymbols($listOfStringSymbols);
        return new Group($sequence);
    }

    /**
     * @param array $listOfStringSymbols
     * @return Group
     */
    public function createAlternationFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $sequence = $this->expressionTestUtils->createChoiceFromListOfStringSymbols($listOfStringSymbols);
        return new Group($sequence);
    }


}
