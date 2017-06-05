<?php

namespace Aleron75\Mage2Hints\Plugin;

use Magento\Framework\App\Area;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\App\State as AppAreaState;
use Magento\TestFramework\Interception\PluginList;
use Magento\TestFramework\ObjectManager;

/**
 * Integration tests developed thanks to
 * Vinai Kopp's Mage2Katas episode 02 - The Plugin Config Kata
 * Watch here: https://youtu.be/zpucxLwY9-0
 */
class LayoutPluginIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $moduleId = 'aleron75_mage2hints';

    /** @var ObjectManager */
    private $objectManager;

    private function setArea($areaCode)
    {
        /** @var AppAreaState $appArea */
        $appArea = $this->objectManager->get(AppAreaState::class);
        $appArea->setAreaCode($areaCode);
    }

    /**
     * @return array
     */
    private function getLayoutPluginInfo()
    {
        /** @var PluginList $pluginList */
        $pluginList = $this->objectManager->create(PluginList::class);

        /** @var LayoutInterface $pluginInfo */
        return $pluginList->get(LayoutInterface::class, []);
    }

    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
    }

    protected function tearDown()
    {
        $this->setArea(null);
    }

    public function testTheModuleInterceptsCallsToLayoutInFrontendScope()
    {
        $this->setArea(Area::AREA_FRONTEND);
        $pluginInfo = $this->getLayoutPluginInfo();
        $this->assertSame(LayoutPlugin::class, $pluginInfo[$this->moduleId]['instance']);
    }

    public function testTheModuleDoesNotInterceptCallsToLayoutInGlobalScope()
    {
        $this->setArea(Area::AREA_GLOBAL);
        $this->assertArrayNotHasKey($this->moduleId, $this->getLayoutPluginInfo());
    }

}