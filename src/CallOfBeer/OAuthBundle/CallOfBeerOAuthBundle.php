<?php

namespace CallOfBeer\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CallOfBeerOAuthBundle extends Bundle
{
	public function getParent()  
    {  
        return 'FOSOAuthServerBundle';  
    }  
}
