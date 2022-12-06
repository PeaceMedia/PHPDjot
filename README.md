# PHPDjot

PHPDjot is a PHP parser for the [Djot](https://djot.net) markup language. Djot is a markup language inspired by Markdown/CommonMark, but intended to both be simpler to parse and avoid ambiguities and difficult edge cases.

(That's the goal, anyway. This is a very early project and there is no actual functional code yet.)

PHPDjot is brought to you by [Peace Media](https://peacemedia.biz).

## Goals

In roughly descending order of importance:

- World peace (note: may be out of scope)
- Output compatibility with the Lua reference implementation (all of the tests in the original project pass with the same output)
- A similar API, internal AST representation, etc to the reference implementation so that future changes can easily be adapted
- Hooks/API to allow for creating custom blocks and inlines

## Tasks

- [ ] Set up tests which parse the test files from the reference implementation's tests directory
- [ ] Build initial mock Djot-to-AST and AST-to-HTML classes far enough that tests run and fail
- [ ] Replace mocks with actual code and get those tests passin'
- [ ] Release Composer package; advertise on HN and elsewhere; get added to Djot's list of implementations if it exists yet
- [ ] Implement custom blocks/inlines API
- [ ] World peace
