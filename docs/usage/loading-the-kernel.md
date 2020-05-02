# Ulrack Kernel - Loading the kernel

The kernel of ulrack uses a set of managers loaded into a single core manager.
This core manager should be passed to the kernel together with an application.
The application will (when run) recieve access to the services layer used in
Ulrack. The application is responsible to implement further logic to give the
application meaning.

The kernel can be easily loaded with the following snippet:
```php
<?php

use Ulrack\Kernel\Component\Kernel\Kernel;
use Ulrack\Kernel\Component\Kernel\Manager\CoreManager;

$coreManager = new CoreManager(__DIR__);

$kernel = new Kernel($coreManager);

```

The directory (`__DIR__`) that is passed to the CoreManager should be the root
of the application. In this location, the application expects to have access to
create a `var` directory for caches, and have access to files in the vendor
directory.

Then in order to run an application, the following snippet can be used:
```php
<?php

use MyVendor\MyPackage\MyNamespace\MyApplication;

$myApplication = new MyApplication();

$kernel->run($myApplication);

```

The application will then get access to the `ServiceManager`, which gives access
to the service layer.

## Further reading

[Back to usage index](index.md)

[Create an application](create-an-application.md)
