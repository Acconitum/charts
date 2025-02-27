<?php

namespace Hoogi91\Charts\Tests\Unit\Domain\Model;

use Hoogi91\Charts\Domain\Model\ChartDataPlain;
use Hoogi91\Charts\Tests\Unit\CacheTrait;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ChartDataPlainTest extends UnitTestCase
{

    use CacheTrait;

    protected $resetSingletonInstances = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpCaches();

        $packageManager = $this->createMock(PackageManager::class);
        $packageManager->method('isPackageActive')->with('spreadsheets')->willReturn(false);
        ExtensionManagementUtility::setPackageManager($packageManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->resetPackageManager();
    }

    public function testTitleMethods(): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setTitle('Lorem Ipsum');
        $this->assertEquals('Lorem Ipsum', $chartData->getTitle());
    }

    public function testTypeMethods(): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setType(ChartDataPlain::TYPE_PLAIN);
        $this->assertEquals(ChartDataPlain::TYPE_PLAIN, $chartData->getType());

        $chartData->setType(ChartDataPlain::TYPE_SPREADSHEET);
        $this->assertEquals(ChartDataPlain::TYPE_PLAIN, $chartData->getType());
    }


    /**
     * @dataProvider labelProvider
     */
    public function testLabelMethods(string $content): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setLabels(trim($content));
        $labels = $chartData->getLabels();
        $this->assertIsArray($labels);
        $this->assertCount(4, $labels);
        $this->assertEquals('Europe', $labels[1]);
    }

    /**
     * @dataProvider datasetProvider
     */
    public function testDatasetMethods(string $content): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setDatasets(trim($content));
        $datasets = $chartData->getDatasets();
        $this->assertIsArray($datasets);
        $this->assertCount(2, $datasets);
        $this->assertIsFloat($datasets[0][0]);
        $this->assertEquals(29.8, $datasets[0][3]);
    }

    /**
     * @dataProvider labelProvider
     */
    public function testDatasetLabelMethods(string $content): void
    {
        $chartData = new ChartDataPlain();
        $chartData->setDatasetsLabels(trim($content));
        $labels = $chartData->getDatasetsLabels();
        $this->assertSame(['Germany'], $labels);
    }

    public function labelProvider(): array
    {
        return [
            'as xml' => [
                'content' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                    <T3TableWizard>
                        <numIndex index="2" type="array">
                            <numIndex index="2">Germany</numIndex>
                            <numIndex index="4">Europe</numIndex>
                            <numIndex index="6">America</numIndex>
                            <numIndex index="8">China</numIndex>
                        </numIndex>
                    </T3TableWizard>',
            ],
            'as typo3 format' => [
                'content' => '|Germany|Europe|America|China|',
            ],
        ];
    }

    public function datasetProvider(): array
    {
        return [
            'as xml' => [
                'content' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                    <T3TableWizard>
                        <numIndex index="2" type="array">
                            <numIndex index="2">16.7</numIndex>
                            <numIndex index="4">15</numIndex>
                            <numIndex index="6">31.2</numIndex>
                            <numIndex index="8">29.8</numIndex>
                            <numIndex index="10">7.3</numIndex>
                        </numIndex>
                            <numIndex index="4" type="array">
                            <numIndex index="2">27.5</numIndex>
                            <numIndex index="4">14.5</numIndex>
                            <numIndex index="6">27.9</numIndex>
                            <numIndex index="8">23.1</numIndex>
                            <numIndex index="10">6.9</numIndex>
                        </numIndex>
                    </T3TableWizard>',
            ],
            'as typo3 format' => [
                'content' => "|16.7|15|31.2|29.8|7.3|\n|27.5|14.5|27.9|23.1|6.9|",
            ],
        ];
    }
}
