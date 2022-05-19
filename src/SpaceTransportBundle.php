<?php

namespace Proglab\SpaceTransportBundle;

//use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Proglab\SpaceTransportBundle\DependencyInjection\ProglabSpaceTransportExtension;

class SpaceTransportBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ProglabSpaceTransportExtension();
    }
}
