# Creating a new app
1. Create `/deploy/public/index.php`:
```
<?php
declare(strict_types = 1);

use BxF\Application;
use BxF\Bootstrapper\Http\Routes;

require_once('../vendor/autoload.php');

(new Application(realpath('../config')))
    ->setBasePath(realpath('../deploy'))
    ->bootstrap([
            //new ErrorHandler(?),
            new Routes(include('../src/Routes.php'))
        ]
    );
```