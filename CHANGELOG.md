# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.1.0] - 2019-04-05
#### Changed
- No longer supporting PHP version <7.2
- Updated code base, including tests, to support PHP7.2+
- `Argument\Iterator::find()` now returns either `null` (instead of `false`) or an instance of `Argument`

## [1.0.2] - 2018-12-02
#### Added
- Added `__toString()` method to `Argument` class.
- Added unit test for `Argument`

## [1.0.1] - 2018-12-02
#### Changed
- Improved regular expression for capturing arguments
- Updated ArgumentIterator unit test

## [1.0.0] - 2018-12-02
#### Added
- Initial release

[1.1.0]: https://github.com/pointybeard/symphony-classmapper/compare/1.0.2...1.1.0
[1.0.2]: https://github.com/pointybeard/symphony-classmapper/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/pointybeard/symphony-classmapper/compare/1.0.0...1.0.1
