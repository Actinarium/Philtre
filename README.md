Philtre
=======

Overview
--------

Philtre is a simple solution for building configurable processing chains with filters and streams.

The idea behind Philtre is:

- to be able to perform data processing, from start to completeness, in a non-hardcoded way (i.e. so that instructions
on how to perform all processing from getting initial data to storing processed data could be declared in editable
configuration rather than source code);
- to be like a pipe, but be able to use as many inputs/outputs as required;
- to be atomary enough (one Filter does one action) for better reusage;
- to be extensible.

I did not find any fitting solutions, therefore implemented one myself.

Currently it's early in development, therefore don't expect it to do much or to have good documentation (although I try
to write meaningful docblocks).

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Actine/Philtre/badges/quality-score.png?s=07778802eec5ba36702f58da881451719a9ba9af)](https://scrutinizer-ci.com/g/Actine/Philtre/)

License
-------

This library is licensed under [BSD 3-Clause license](http://opensource.org/licenses/BSD-3-Clause).

Philtre's architecture
----------------------

The main concepts of Philtre are:

- **Filter** — an object that implements `process()` method, which takes care of processing input and producing output;
- **FilterContext** — an object where one or multiple Filters take input from and store output to. Multiple Filters may
share one FilterContext to reuse the same resources, as well as every Filter can be "sandboxed" in its own FilterContext.
- **ExecutionManager** — an object that takes care of creating Filters, assigning them to FilterContexts and invoking
the processing chain, all dynamically based on provided configuration.

Configuration
-------------

### Exchange strategies (dev documentation) ###

Requires and exports can use the following strategies:

- **default** — in that case getting and setting data will follow the same rules as assignment in PHP: scalars will be
copied, objects will be passed by reference.
- **deep-copy** — in that case value will be copied using `unserialize(serialize($value))`. Recommended if you want to
ensure that there will be no way for a filter to change provided data on any level, but also the most resource-expensive
mode.
- **clone** — will use `clone` method for objects. Unless custom `__clone()` method is implemented on an object, this
will create a shallow copy of an object. **Warn:** Objects inside arrays will still be passed by reference. Use this if
your streams contain objects with custom clone logic.
- **wrapped** — will act the same way as _uses_, i.e. pass wrapped data. You should go with this way only if you are
sure that wrapped objects can be safely provided to filters (that they won't alter provided data), and you want to use
_requires_ and _exports_ instead of _uses_ for semantics. Also the fastest mode.
