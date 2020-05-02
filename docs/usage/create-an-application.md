# Ulrack Kernel - Create an application

To run custom code using the kernel of Ulrack, an application needs to be
created. The application should implement the
[ApplicationInterface](../../src/Common/ApplicationInterface.php). The interface
expects one method to be implemented by the application. In the following
example an environment variable is being emited to the user. To do this, first
follow the following guide to support autoloading configuration:
[GrizzIT Configuration - Adding a locator](https://github.com/grizz-it/configuration/blob/master/docs/usage/adding-a-locator.md).

When this is done, create the file `configuration/parameters/my-parameters.json`.
The contents of the file for this example will be:
```json
{
    "my-parameter": "${SHELL}"
}
```

Then we create a php file for our application, with the following contents:
```php
<?php

namespace MyVendor\MyPackage\MyNamespace;

use Ulrack\Kernel\Common\ApplicationInterface;

class MyApplication implements ApplicationInterface
{
    /**
     * Runs the application.
     *
     * @return void
     */
    public function run(ServiceManagerInterface $serviceManager): void
    {
        echo $serviceManager->getServiceFactory()
            ->create('parameters.my-parameter');
    }
}

```

Now the application can be loaded and passed to the run method of the Kernel.
When executing this file the output should be, depending on the shell that is
being used:
```
/bin/bash
```

When changes are made the configuration, throw away the `var` directory to load
the new configuration. This directory is used to build up a cache of the
application to ensure the following requests are way faster.

## Further reading

[Back to usage index](index.md)

[Create an application](loading-the-kernel.md)
