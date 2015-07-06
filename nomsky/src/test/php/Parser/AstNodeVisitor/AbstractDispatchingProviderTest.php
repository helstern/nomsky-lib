<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

class AbstractDispatchingProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test double dispatching with a fake node
     */
    public function testDoubleDispatchWithTestFake()
    {
        $fakeAstNode = new FakeAstNode();
        $expectedVisitor = $this->getMockForAbstractClass('\\Helstern\Nomsky\Parser\\Ast\\AstNodeVisitor');

        /** @var \PHPUnit_Framework_MockObject_MockObject|AbstractDispatchingProvider $mock */
        $mock = $this->getMockBuilder('\\Helstern\Nomsky\Parser\\AstNodeVisitor\\AbstractDispatchingProvider')
            ->setMethods(array('getFakeAstNodeVisitor'))
            ->getMockForAbstractClass();
        $mock->expects($this->once())->method('getFakeAstNodeVisitor')->with($fakeAstNode)->willReturn($expectedVisitor);
        $actualVisitor = $mock->getVisitor($fakeAstNode);

        $this->assertSame($expectedVisitor, $actualVisitor, 'Double dispatching does not works');
    }
}
