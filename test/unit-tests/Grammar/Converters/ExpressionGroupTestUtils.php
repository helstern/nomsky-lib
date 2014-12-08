<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Group;

class ExpressionGroupTestUtils
{
    /** @var ExpressionTestUtils  */
    protected $expressionTestUtils;

    public function __construct(ExpressionTestUtils $expressioTestUtils)
    {
        $this->expressionTestUtils = $expressioTestUtils;
    }

    /**
     * @param array $symbols
     * @return Group
     */
    public function createSequenceFromSymbols(array $symbols)
    {
        $sequence = $this->expressionTestUtils->createSequenceFromSymbols($symbols);
        return new Group($sequence);
    }

    /**
     * @param array $symbols
     * @return Group
     */
    public function createAlternationFromSymbols(array $symbols)
    {
        $sequence = $this->expressionTestUtils->createAlternationFromSymbols($symbols);
        return new Group($sequence);

    }

    /**
     * @param array $listOfStringSymbols
     * @return Group
     */
    public function createSequenceFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $sequence = $this->expressionTestUtils->createSequenceFromListOfStringSymbols($listOfStringSymbols);
        return new Group($sequence);
    }

    /**
     * @param array $listOfStringSymbols
     * @return Group
     */
    public function createAlternationFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $sequence = $this->expressionTestUtils->createAlternationFromListOfStringSymbols($listOfStringSymbols);
        return new Group($sequence);
    }


}
