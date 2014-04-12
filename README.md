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
