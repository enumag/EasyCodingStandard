<?php

namespace Symplify\EasyCodingStandard\Bootstrap;

use ECSPrefix20210516\Symfony\Component\Console\Input\InputInterface;
use Symplify\EasyCodingStandard\Set\ConstantReflectionSetFactory;
use Symplify\EasyCodingStandard\Set\EasyCodingStandardSetProvider;
use ECSPrefix20210516\Symplify\SetConfigResolver\SetAwareConfigResolver;
use ECSPrefix20210516\Symplify\SetConfigResolver\ValueObject\Bootstrap\BootstrapConfigs;
/**
 * @deprecated Move to direct $containerConfigurator->import() approach, instead of our hidden nested magic with same result
 */
final class ECSConfigsResolver
{
    /**
     * @var SetAwareConfigResolver
     */
    private $setAwareConfigResolver;
    public function __construct()
    {
        $easyCodingStandardSetProvider = new \Symplify\EasyCodingStandard\Set\EasyCodingStandardSetProvider(new \Symplify\EasyCodingStandard\Set\ConstantReflectionSetFactory());
        $this->setAwareConfigResolver = new \ECSPrefix20210516\Symplify\SetConfigResolver\SetAwareConfigResolver($easyCodingStandardSetProvider);
    }
    /**
     * @return \Symplify\SetConfigResolver\ValueObject\Bootstrap\BootstrapConfigs
     */
    public function resolveFromInput(\ECSPrefix20210516\Symfony\Component\Console\Input\InputInterface $input)
    {
        $configFileInfos = [];
        $mainConfigFileInfo = $this->setAwareConfigResolver->resolveFromInputWithFallback($input, ['ecs.php']);
        if ($mainConfigFileInfo !== null) {
            // 2. "parameters > set" in provided yaml files
            $parameterSetsConfigs = $this->setAwareConfigResolver->resolveFromParameterSetsFromConfigFiles([$mainConfigFileInfo]);
            $configFileInfos = \array_merge($configFileInfos, $parameterSetsConfigs);
        }
        return new \ECSPrefix20210516\Symplify\SetConfigResolver\ValueObject\Bootstrap\BootstrapConfigs($mainConfigFileInfo, $configFileInfos);
    }
}
