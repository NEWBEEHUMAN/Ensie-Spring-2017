<?php

namespace Ensie\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnsieUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
