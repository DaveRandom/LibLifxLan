# LIFX LAN

PHP implementation of the [LIFX LAN protocol](https://lan.developer.lifx.com/docs/introduction).

WIP. Do not rely on any of this code as the API is currently subject to change.

## Requirements

- PHP 7.1.1, because floating point endianness [specifiers](https://secure.php.net/pack#refsect1-function.pack-changelog)
  for `pack()`/`unpack()` are used.
- 64-bit PHP builds, mostly because timestamp fields use 64-bit integers with a value expressed in nanoseconds since
  unix epoch. This requirement could be removed with some work, all that is needed is a pair of routines to convert
  to/from a `DateTimeImmutable` object and this (somewhat odd?) integer representation.

## @todo

- Implement multi-zone messages
- Write tests
- Write a client implementation with transport handling
