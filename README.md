# Jank Profiler

For when you don't want to figure out actual profilers.

Use Jank Profiler to wrap individual objects in your application to track memory usage and 
execution time of method calls belonging to that objects public api.

## Usage

Let's say you have some code in your application that looks like this:

```php
$myQuestionableObject = new WhompWhompDoDad(['thinger_option_x' => true]);
$whompTimes = 3;
$myQuestionableObject->whomp($whompTimes);
$myQuestionableObjecct->doDad();
```

you can jank profile the `WhompWhompDoDad` instance like this:

```php
$myQuestionableObject = new JankProfiler('WhompWhompDoDad', [['thinger_option_x' => true]]);
$whompTimes = 3;
$myQuestionableObject->whomp($whompTimes);
$myQuestionableObjecct->doDad();

echo $myQuestionableObject->report('json') . "\n"; exit;
```

This will produce JSON output similar to:

```
[
    {
        "type": "construct",
        "meta": {
            "classname": "WhompWhompDoDad",
            "constructor_args": [
                {
                    "thinger_option_x":  true
                }
            ]
        },
        "memory_before": 464280,
        "start_time": 1425158122.1155,
        "memory_after": 477968,
        "end_time": 1425158122.116
    },
    {
        "type": "method call",
        "meta": {
            "method": "whomp",
            "arguments": [
                3
            ]
        },
        "start_time": 1425158122.116,
        "memory_before": 479896,
        "end_time": 1425158122.116,
        "memory_after": 480312
    },
    {
        "type": "method call",
        "meta": {
            "method": "doDad",
            "arguments": []
        },
        "start_time": 1425158122.116,
        "memory_before": 481976,
        "end_time": 1425158122.116,
        "memory_after": 482344
    }
]
```

Pretty neat!

## Author

Colin DeCarlo, colin@thedecarlos.ca

## License

Jank Profiler is licensed under the MIT License - see the LICENSE file for details
