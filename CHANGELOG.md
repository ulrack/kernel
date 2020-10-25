# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 1.3.3 - 2020-10-25
### Fixed
- PHP warnings were thrown when `ext-yaml` was not installed.
 - Fixed by removing the default codec registration for yaml.

## 1.3.2 - 2020-09-06
### Fixed
- Allowing numbers in class declarations.

## 1.3.1 - 2020-08-17
### Fixed
- Parsing arrays when using arrays as parameters.

## 1.3.0 - 2020-06-14
### Added
- Ability to retrieve the cache file system from the cache manager.

## 1.2.1 - 2020-06-09
### Fixed
- Loading extra service extensions configuration by their keys.
- Setting the package name through an extension.

## 1.2.0 - 2020-06-08
### Added
- Compatibility with ulrack/services version 3.0
- Method to retrieve the method reflector from the object manager.

## 1.1.2 - 2020-06-03
### Fixed
- Compatibility with ulrack/services version 2.0

## 1.1.1 - 2020-05-02
### Fixed
- Registration for the environment variable hook.

## 1.1.0 - 2020-05-02
### Added
- Added the ability to reset the registered caches for the CacheManager.

## 1.0.1 - 2020-05-02
### Fixed
- Fixed a bug in the ServiceManager where an unnecessary prefix was added.

## 1.0.0 - 2020-05-02
### Added
- The initial implementation of the package.

# Versions
- [1.3.3 > Unreleased](https://github.com/ulrack/kernel/compare/1.3.3...HEAD)
- [1.3.2 > 1.3.3](https://github.com/ulrack/kernel/compare/1.3.2...1.3.3)
- [1.3.1 > 1.3.2](https://github.com/ulrack/kernel/compare/1.3.1...1.3.2)
- [1.3.0 > 1.3.1](https://github.com/ulrack/kernel/compare/1.3.0...1.3.1)
- [1.2.1 > 1.3.0](https://github.com/ulrack/kernel/compare/1.2.1...1.3.0)
- [1.2.0 > 1.2.1](https://github.com/ulrack/kernel/compare/1.2.0...1.2.1)
- [1.1.2 > 1.2.0](https://github.com/ulrack/kernel/compare/1.1.2...1.2.0)
- [1.1.1 > 1.1.2](https://github.com/ulrack/kernel/compare/1.1.1...1.1.2)
- [1.1.0 > 1.1.1](https://github.com/ulrack/kernel/compare/1.1.0...1.1.1)
- [1.0.1 > 1.1.0](https://github.com/ulrack/kernel/compare/1.0.1...1.1.0)
- [1.0.0 > 1.0.1](https://github.com/ulrack/kernel/compare/1.0.0...1.0.1)
