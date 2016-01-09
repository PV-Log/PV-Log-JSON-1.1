# PV-Log JSON 1.1

## Generator classes SDK

To participate in [PV-Log](http://pv-log.com) with a not directly supported data logger you can use these PHP classes to generate **compatible** and **valid** PV-Log JSON 1.1 files.

Data assembled with these SDK classes can be used either for direct push via API or to provide files via FTP or URL request.

* For format details please refer to the official technical description of the [PV-Log JSON 1.1 format](http://goo.gl/kPq6Zu).
* For class structure and usage please refer to the enclosed documentation.
* To find out, what's possible with and how the handle the classes, take a look into the `tests` directory.

## Example for minutes file format (data within a day)

As example I'll show you, how to build data for a plant with 1 inverter with 2 strings. (Strings supported for Gold users)

We will build the following structure (instance stands here for the installation):

      instance
      - plant
        - inverter
          - string 1
            - powerAcWatts
          - string 2
            - powerAcWatts
          - powerAcWatts
          - totalWattHours
        - powerAcWatts
        - totalWattHours

Assume, you have data for string DC powers, inverter AC powers and inverter energy over the day.

It is **not** required, that the timestamps of your data align to PV-Log 5 minutes interval, they will be corrected during output, holes in the timeline will be interpolated also.

    <?php

    use PVLog\Classes\Json\Instance; // Installation level class atop over all
    use PVLog\Classes\Json\Plant;    // Plant node class
    use PVLog\Classes\Json\Inverter; // Inverter node class
    use PVLog\Classes\Json\Strings;  // String node class

    // Let's start with the node objects
    $installation = new Instance;
    $plant        = new Plant;
    $inverter     = new Inverter;
    $string1      = new Strings;
    $string2      = new Strings;

    // I suggest to build the hierarchy from the inside to the outside
    // Assume, there are functions that provides the data as array
    // with timestamps as keys and the measuring data as values
    $data = getPowerDataForString1();

    // Add data row by row ...
    foreach ($data as $timestamp=>$value) {
        // add... used for 1 of 0..n
        $string1->addPowerAcWatts($timestamp, $value);
    }
    // ... or with this array structure at once
    // set... used for 1 of 0..1
    # $string1->setPowerAcWatts($data);

    // Now add the string to the inverter
    $inverter->addString($string1);
    // or wise versa
    # $string1->addToInverter($inverter);

    // Now make the same for the other string, in real life
    // you have probably a loop for this ...

    // Same procedure for inverter data
    $data = getPowerDataForInverter();
    $inverter->setPowerAcWatts($data);
    $inverter->setTotalWattHours(getEnergyDataForInverter());

    // Extend hierarchy
    $plant->addInverter($inverter);
    $installation->setPlant($plant);
    // Or with fluid interface
    # $installation->setPlant($plant->addInverter($inverter));

    // That's all about building data.

But wait, what's about **powers** and **energy** of **plant** level?

No problem, they are optional and will be **calculated automatic** from Inverters assuming there are no additional losses.

The same is for **power** and **energy** data and **vise versa**. If **one** of them is **missing**, it will be **calculated from the existing one** during output.

If you build data for solar parks with dozens of inverters (especially connected to the medium voltage grid or with a converter counter) the plant internal losses in power and/energy could be interesting to know and show.

    // We build the data, what's next
    # $installation->setTypeMinutes(); // is not required, is default

    // There are 3 main methods to get valid JSON data out there

    // 1. Get single line of JSON data
    $json = $installation->asJson();
    $json = $installation->asJson(TRUE); // prettified

    // 2. Output single line of JSON data via magic __toString() method
    echo $installation;

    // 3. Store the data somewhere, e.g. into file system
    $fileName = '/path/to/file.json';
    $writtenBytes = $installation->saveToJsonFile($fileName);
    $writtenBytes = $installation->saveToJsonFile($fileName, TRUE); // prettified
    if ($writtenBytes === FALSE) {
        echo 'Ups, something went wrong, couldn\'t save data, ',
             'please check file/directory permissions';
    }

If you have data for **more** than **one day** in your structure, you can also direct generate `days` or `months` files.

    // Get data for fileContent == days
    echo $installation->setTypeDays();

    // Get data for fileContent == months
    echo $installation->setTypeMonths();
