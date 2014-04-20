# Philtre #

Philtre is a simple solution for building configurable processing chains with filters and streams.

Currently the library is early in development, and I cannot afford to spend more time on this now. In fact, I needed
this library myself for a project that is soon due, but decided to share it since someone else might find it useful.
So please forgive poor documentation for now, and take a look at phpdoc / tests to understand how it works instead.

[![Build Status](https://travis-ci.org/Actine/Philtre.svg?branch=master)](https://travis-ci.org/Actine/Philtre)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Actine/Philtre/badges/quality-score.png?s=07778802eec5ba36702f58da881451719a9ba9af)](https://scrutinizer-ci.com/g/Actine/Philtre/)

## License ##

This library is licensed under [BSD 3-Clause license](http://opensource.org/licenses/BSD-3-Clause).

## Philtre's architecture ##

The main concepts of Philtre are:

- **Filter** — an object that implements `process()` method, which takes care of processing input and producing output.
A filter should perform one atomary operation, e.g. populate text template with data, perform regex replacement, read
data from disk, parse input into objects etc. Everything that filter produces or operates on, it should retrieve from
and store to the context. Filters also can take parameters, which are semantically more like configuration than data.
- **FilterContext** — an object where one or multiple filters take input from and store output to. Multiple filters may
share one FilterContext to reuse the same resources, as well as every filter can be "sandboxed" in its own FilterContext.
There are three types of FilterContexts bundled: `SimpleFilterContext`, `StreamedFilterContext` and `WiringFilterContext`.
- **ExecutionManager** — takes care of creating filters, assigning them to filter contexts and invoking the processing
chain, all dynamically based on provided configuration. There is one execution manager included: `BundledPipeline`.

The library encourages to implement your own Filters, Contexts and ExecutionManagers by providing a set of interfaces.

**todo:** complete documentation once have time.

## Pipeline descriptor ##

This is an explanation of _test/Actinarium/Philtre/Test/Resources/reference_config.json_ pipeline descriptor that is
used in `BundledPipelineTest`.

The configuration is stored in JSON for convenience. It can be any format you like — what counts is that in the end of
the day you provide a properly composed object (or assoc array, but better object) to BundledPipeline's constructor.

### Filters aliasing ###

```json
    "filters" : {
        "fixture" : "\\Actinarium\\Philtre\\Test\\Resources\\FixtureFilter"
    }
```

This configuration allows to alias filter classes to ID's. Usually this is a global application config and is mixed into
passed configuration, rather than duplicated in every pipeline descriptor

### Initial data ###

```json
    "initStreams" : {
        "INPUT1" : "BaseOne",
        "INPUT2" : "BaseTwo"
    }
```

Here you may define the streams that should be initialized before filters start processing data. Key is stream ID within
the manager, value can be anything that is valid for your processing chain (in this case, both are strings).

### Data to return ###

```json
    "return" : {
        "input1"   : "INPUT1",
        "input2"   : "INPUT2",
        "input2-1" : "INPUT2EDITEDONCE",
        "input2-2" : "INPUT2EDITEDTWICE",
        "output"   : "OUTPUT",
        "output-2" : "OUTPUTTWICE"
    }
```

This block means: return associative array where $result['input1'] has data from stream INPUT1, $result['output-2'] has
data from stream OUTPUTTWICE etc. **Note:** All of these streams should be present at the moment processing is done,
otherwise exception will be thrown.

There are two different notations:

```json
    "return" : ["INPUT1", "INPUT2", "INPUT2EDITEDONCE", "INPUT2EDITEDTWICE", "OUTPUT", "OUTPUTTWICE"],
```

This means, return associative array where the key is stream ID (e.g. $result['INPUT2EDITEDONCE']). Basically it's
similar to the previous way but without aliasing stream ID's.

```json
    "return" : "OUTPUT"
```

This means that data from only one string will be returned without being wrapped in assoc array.

### Filter chain ###

Filter chain is optional, however it doesn't make much sense to create a chain without filters, right?

```json
    "chain" : [
        {
            "filter"     : "fixture",
            "context"    : "C1",
            "parameters" : {
                "suffix" : "First"
            },
            "inject"     : {
                "IN1" : "INPUT1",
                "IN2" : "INPUT2"
            },
            "extract"    : {
                "OUT" : "OUTPUT"
            }
        },
        {
            // filter #2 descriptor
        },
        ...
        {
            // filter #n descriptor
        }
    ]
```

Chain defines a list of filters that will process data sequentially in the order how they appear on the list.

**Note:** Processing is not transactional, meaning that if some filter changes the state (e.g. writes data to disk), and
then the following filter fails, the changes will not be rolled back. That's why you are advised to perform
state-changing operations in the very end.

### Filter chain descriptor ###

#### filter ####

```json
    "filter" : "fixture"
```

```json
    "filter" : "\\Actinarium\\Philtre\\Test\\Resources\\FixtureFilter"
```

Mandatory. You can use either ID of the filter or full classname.

#### context ####

```json
    "context" : "C1"
```

Optional. If you want multiple filters to share one context, you have to implicitly specify the same context ID (can be
any string). For sandboxed filters that don't share context, you can safely omit context declaration, and the manager
will create an anonymous one just for that filter.

#### parameters ####

```json
    "parameters" : {
        "suffix" : "Second",
        ...
    }
```

`parameters` holds data that will be passed to current Filter's constructor.

#### inject ####

```json
    "inject" : {
        "IN1" : "INPUT1",
        "IN2" : "INPUT2"
    }
```

This line instructs to put INPUT1 and INPUT2 streams from the manager into current filter's context under ID's IN1 and
IN2 respectively before the filter is triggered. These should respect filter's required and used streams.

The manager is obliged to provide all required streams required by current filter into its context.

**Note:** If several filters share one context, you can inject data to the first of them only, unless you want to
replace the data in shared context midway.

#### extract ####

```json
    "extract"    : {
        "IN2" : "INPUT2EDITEDTWICE",
        "OUT" : "OUTPUTTWICE"
    }
```

This line instructs to pull IN2 and OUT streams from the current filter's context into INPUT2EDITEDTWICE and OUTPUTTWICE
manager streams respectively after the filter has completed processing. These should respect filter's exported and used
streams.

The manager is not obliged to get filter's output if it doesn't need it in further processing or as return value.

**Note:** If several filters share one context, you can extract data from the last of them only, unless you want to
extract intermediate data.

#### Reference Config flow explained ####

Here what happens when BundledPipelineTest works:

1. INPUT1 and INPUT2 are initialized with values "BaseOne" and "BaseTwo".
2. After the first filter fires, the manager stores current IN2 value "BaseTwo_BaseOne" from the context into
INPUT2EDITEDONCE stream, and current OUT value ("BaseOne_BaseTwo_First") into OUTPUT stream. Note that at this point
neither INPUT1 nor INPUT2 get changed: data for IN2 is edited within filter's context, not manager's context.
3. For the second filter, the manager supplies INPUT1 and INPUT2 but never gets any output. Also the manager creates a
named context for the filter, but it's not shared with any other filter, so that's kinda redundant.
4. The third filter is initialized in the same context as the first one. It already has IN1, IN2 and OUT values, IN2
being "BaseTwo_BaseOne". The manager overwrites IN1 in the context with OUTPUT stored from the first filter
("BaseOne_BaseTwo_First"), and then extracts again changed IN2 ("BaseTwo_BaseOne_BaseOne_BaseTwo_First") into
INPUT2EDITEDTWICE, and OUT ("BaseOne_BaseTwo_First_BaseTwo_BaseOne_Third") into OUTPUTTWICE.
5. The fourth filter gets created with anonymous context; it takes in INPUT1 and INPUT2 (still unchanged) and produces
OUTPUT ("BaseOne_BaseTwo_Fourth"), which overwrites the OUTPUT we had from the 1st filter and passed to the 3rd.

Hope this makes sense.
