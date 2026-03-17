# Changelog

## [Unreleased]

### Added

- `Innmind\Url\Url::resolve()`

### Fixed

- Urls made only of relative paths were improperly parsed

## 5.1.0 - 2026-01-24

### Changed

- Require PHP `8.5`
- User, password, query and fragment values are not url encoded (allowing for previously invalid values)

## 5.0.0 - 2026-01-18

### Added

- `Innmind\Url\Url::attempt()`

### Changed

- Require PHP `8.4`
- `Innmind\Url\Url` constructor is now private, use `::from()` instead
- Passwords are now longer included in error messages

### Removed

- `Innmind\Url\Exception\Exception`
- `Innmind\Url\Exception\DomainException`
- `Innmind\Url\Exception\PasswordCannotBeSpecifiedWithoutAUser`

## 4.4.0 - 2025-03-20

### Added

- Support for `innmind/black-box` `6`

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
