# Changelog

## 4.3.2 - 2024-10-26

### Fixed

- The password was still being visible inside the `Innmind\Url\Authority\UserInformation::$string` property. The property has been removed.

## 4.3.1 - 2024-10-19

### Changed

- `Innmind\Url\Authority\UserInformation\Password` inner value is now stored inside a `\SensitiveParameterValue` to prevent a password being accidently displayed in a dump/log.

## 4.3.0 - 2023-09-16

### Added

- Support for `innmind/immutable` `5`

## 4.2.0 - 2023-07-08

### Changed

- Require `innmind/black-box` `5`

### Removed

- Support for PHP `8.0` and `8.1`

## 4.1.0 - 2022-01-23

### Changed

- Bump minimum version of [`league/uri-components`](https://packagist.org/packages/league/uri-components) to `~2.0`
