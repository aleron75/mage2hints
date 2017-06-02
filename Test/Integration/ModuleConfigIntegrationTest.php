<?php

namespace Aleron75\Mage2Hints\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\DeploymentConfig\Reader as DeploymentConfigReader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\ObjectManager;

/**
 * Integration tests developed thanks to
 * Vinai Kopp's Mage2Katas episode 01 - The Module Skeleton Kata
 * Watch here: https://youtu.be/JvBWJ6Lm9MU
 */
class ModuleConfigIntegrationTest extends \PHPUnit_Framework_TestCase
{
    private $moduleName = 'Aleron75_Mage2Hints';

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $this->assertArrayHasKey($this->moduleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }

    public function testTheModuleIsConfiguredAndEnabledInTestEnvironment()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class);

        $this->assertTrue($moduleList->has($this->moduleName), 'The module is not enabled in test env');
    }

    public function testTheModuleIsConfiguredAndEnabledInRealEnvironment()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = ObjectManager::getInstance();

        /** @var DirectoryList $dirList */
        $dirList = $objectManager->create(DirectoryList::class, ['root' => BP]);

        /** @var DeploymentConfigReader $configReader */
        $configReader = $objectManager->create(DeploymentConfigReader::class, ['dirList' => $dirList]);

        /** @var DeploymentConfig $deploymentConfig */
        $deploymentConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);

        /** @var ModuleList $moduleList */
        $moduleList = $objectManager->create(ModuleList::class, ['config' => $deploymentConfig]);

        $this->assertTrue($moduleList->has($this->moduleName), 'The module is not enabled in real env');
    }
}