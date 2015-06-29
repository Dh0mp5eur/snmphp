# snmphp
PHP library for monitoring various devices via SNMP

Requires php5-snmp package to be installed

Currently supported:
 * Synology: disk information (tested on RS815+)

Support list is very small for now but you can see where this project is headed.

There's lots to do (adding devices, error handling...) so feel free to contribute.

##### Sample output on Synology RS851
```
Array
(
    [0] => Array
        (
            [Name] => Disk 1
            [PartID] => WD30EFRX-68EUZN0
            [Type] => SATA
            [Temperature] => 36
            [Status] => Normal
        )

    [1] => Array
        (
            [Name] => Disk 2
            [PartID] => WD30EFRX-68EUZN0
            [Type] => SATA
            [Temperature] => 37
            [Status] => Normal
        )

    [2] => Array
        (
            [Name] => Disk 3
            [PartID] => WD30EFRX-68EUZN0
            [Type] => SATA
            [Temperature] => 36
            [Status] => Normal
        )

    [3] => Array
        (
            [Name] => Disk 4
            [PartID] => WD30EFRX-68EUZN0
            [Type] => SATA
            [Temperature] => 35
            [Status] => Normal
        )

)
```
