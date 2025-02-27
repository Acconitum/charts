<?php

namespace Hoogi91\Charts\Tests\Unit\Domain\Model;

use Hoogi91\Charts\Domain\Model\ChartDataPlain;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ChartDataPlainColorTest extends UnitTestCase
{

    protected $resetSingletonInstances = true;

    /**
     * @dataProvider backgroundDataProvider
     */
    public function testBackgroundColorMethods(
        array $colors,
        int $expectedCount,
        array $expectedColors
    ): void {
        $chartData = new ChartDataPlain();
        $chartData->setBackgroundColors($colors);
        $this->assertCount($expectedCount, $chartData->getBackgroundColors());
        $this->assertSame($expectedColors, $chartData->getBackgroundColors());
    }

    public function backgroundDataProvider(): array
    {
        return [
            'empty backgrounds are dropped' => [
                'backgrounds' => [null, '', '0'],
                'expectedCount' => 0,
                'expectedColors' => [],
            ],
            'only non empty backgrounds are kept' => [
                'backgrounds' => ['rgb(0, 0, 255)', null, 'rgb(0, 128, 0)'],
                'expectedCount' => 2,
                'expectedColors' => ['rgb(0, 0, 255)', 'rgb(0, 128, 0)'],
            ],
        ];
    }

    /**
     * @dataProvider borderDataProvider
     */
    public function testBorderColorMethods(array $colors, int $expectedCount, array $expectedColors): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setBorderColors($colors);
        $this->assertCount($expectedCount, $chartData->getBorderColors());
        $this->assertSame($expectedColors, $chartData->getBorderColors());
    }

    public function borderDataProvider(): array
    {
        return [
            'empty borders are dropped' => [
                'borders' => [null, '', '0'],
                'expectedCount' => 0,
                'expectedColors' => [],
            ],
            'only non empty borders are kept' => [
                'borders' => ['rgb(0, 0, 255)', null, 'rgb(0, 128, 0)'],
                'expectedCount' => 2,
                'expectedColors' => ['rgb(0, 0, 255)', 'rgb(0, 128, 0)'],
            ],
        ];
    }
}
