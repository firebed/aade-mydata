# Contributing

Every business in Greece must send its invoices to myDATA, and many need a developer to connect their
software to it. This library aims to make that easier. It is hard to cover every need alone, so your help
is welcome.

> [!TIP]
> Any contribution helps: an idea, a suggestion, a bug report or code.

You can contribute by:

- forking the [repository](https://github.com/firebed/aade-mydata) and opening a pull request
- opening an [issue on GitHub](https://github.com/firebed/aade-mydata/issues)
- sending an email to [dev@invoicemaker.gr](mailto:dev@invoicemaker.gr)

## Rules

- Open pull requests against `6.x`. Send fixes for version 5 to `5.x`.
- Code style: Laravel Pint. Run `composer lint` (check) or `composer fix`.
- Static analysis: PHPStan. Run `composer analyse`.
- Add tests for code changes. Run `composer test`. A code change without tests is not accepted.
- CI runs all three on PHP 8.3, 8.4 and 8.5, with Guzzle 7 and 8. Keep it green.
- Document any change in behaviour in `docs/`. If it breaks existing code, update the upgrade guides too.
- One pull request per feature. For more features, send more pull requests.
- Write commit messages as [Conventional Commits](https://www.conventionalcommits.org/), for example `fix(http): ...` or `docs: ...`.
- If you made many commits, squash them into one before you push.
- Don't bump versions or tag releases. The maintainer does that.
- Never commit your myDATA user ID or subscription key.
