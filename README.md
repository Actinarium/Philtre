Philtre
=======

Philtre is a minimalistic library for various Pipe & Filters scenarios.

Currently it's early in development, therefore the only supported scenario is an unchecked pipe with sequential
execution.

Philtre's architecture
----------------------

The main concepts of Philtre are:

- **Filter** — an object that implements `process()` method, which takes care of processing input and producing output;
- **FilterContext** — an object where one or multiple Filters take input from and store output to. Multiple Filters may
share one FilterContext to reuse the same resources, as well as every Filter can be "sandboxed" in its own FilterContext.
- **PipelineManager** — an object that takes care of creating Filters, assigning them to FilterContexts and invoking the
processing chain, all dynamically based on provided configuration.

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
