<?php

use BePaidAcquiring\BePaidClient;

if (file_exists('_client_credentials.local.php')) {
    return require '_client_credentials.local.php';
}

return new BePaidClient(362, '9ad8ad735945919845b9a1996af72d886ab43d3375502256dbf8dd16bca59a4e', ['test' => true]);
//return new BePaidClient(361, 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d', ['test' => true]);
