<?php
/*
 * This file is part of the reva2/jsonapi.
 *
 * (c) OrbitScripts LLC <support@orbitscripts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Reva2\JsonApi\Tests\Http\Query;

use Reva2\JsonApi\Http\Query\QueryParameters;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * Test for JSON API query parameters
 *
 * @package Reva2\JsonApi\Tests\Http\Query
 * @author Sergey Revenko <reva2@orbita1.ru>
 */
class QueryParametersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 400
     */
    public function shouldThrowOnInvalidIncludePaths()
    {
        $query = new QueryParameters();
        $query->parseIncludePaths(null);
        $query->parseIncludePaths(['invalid']);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 400
     */
    public function shouldThrowOnInvalidFieldsets()
    {
        $query = new QueryParameters();
        $query->parseFieldSets(null);
        $query->parseFieldSets('invalid');
    }

    /**
     * @test
     */
    public function shouldValidateIncludePaths()
    {
        $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
        $violation
            ->expects($this->once())
            ->method('setParameter')
            ->with('%paths%', "'store', 'store.owner'")
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setPlural')
            ->with(2)
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setInvalidValue')
            ->with(['store', 'store.owner'])
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setCode')
            ->with(QueryParameters::INVALID_INCLUDE_PATHS)
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('atPath')
            ->with('includePaths')
            ->willReturnSelf();

        $violation->expects($this->once())->method('addViolation');

        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        $context
            ->expects($this->once())
            ->method('buildViolation')
            ->with('Invalid include paths: %paths%')
            ->willReturn($violation);


        $query = new QueryParameters();
        $query->setIncludePaths(['store', 'store.owner'])->validateIncludePaths($context);
        $query->setIncludePaths(null)->validateIncludePaths($context);
    }

    /**
     * @test
     */
    public function shouldValidateFieldSets()
    {
        $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
        $violation
            ->expects($this->once())
            ->method('setParameter')
            ->with('%fields%', "'name', 'family'")
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setPlural')
            ->with(2)
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setInvalidValue')
            ->with(['name', 'family'])
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('setCode')
            ->with(QueryParameters::INVALID_FIELD_SET)
            ->willReturnSelf();

        $violation
            ->expects($this->once())
            ->method('atPath')
            ->with('fieldSets.pets')
            ->willReturnSelf();

        $violation->expects($this->once())->method('addViolation');

        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        $context
            ->expects($this->once())
            ->method('buildViolation')
            ->with('Invalid fields: %fields%')
            ->willReturn($violation);

        $query = new QueryParameters();
        $query->setFieldSets(['pets' => ['name', 'family']])->validateFieldSets($context);
        $query->setFieldSets(null)->validateFieldSets($context);
    }

    /**
     * @test
     */
    public function shouldNotBeEmptyIfOneOfParameterWasSpecified()
    {
        $query1 = new QueryParameters();
        $this->assertTrue($query1->isEmpty());

        $query1->setIncludePaths(['store', 'store.owner']);
        $this->assertFalse($query1->isEmpty());

        $query2 = new QueryParameters();
        $query2->setFieldSets(['pets' => ['name', 'family']]);
        $this->assertFalse($query2->isEmpty());
    }
}
