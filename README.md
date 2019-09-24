# Grump PHP config for Drupal Projects

## Twig CS

### Whitelist

`/^(docroot|web)\/modules\/custom\/(.*)/ `

`/^(docroot|web)\/themes\/custom\/(.*)/`

`/^(docroot|web)\/themes\/patternlab\/(.*)/`

## Json lint

### Ignore

`'/^(?!docroot\/modules\/custom).*/'`

`'/^(?!web\/modules\/custom).*/'`

## Git commit messages

No empty messages allowed.

Suffix must be in commit messaged.

Allowed suffixes (Type scope conventions)

- build
- ci
- chore
- docs
- feat
- fix
- new
- upd
- perf
- refactor
- revert
- style
- test
- chg

## PHP Stan

Ignore all contrib code.

### Whitelist

`'/^(?!docroot\/modules\/custom).*/'`

`'/^(?!web\/modules\/custom).*/'`

## Yaml lint

Check all yamls.

### Whitelist

`/^(docroot|web)\/modules\/custom\/(.*)/`

`/^(docroot|web)\/themes\/custom\/(.*)/`

## Git blacklist

Following code is not allowed

- "die("
- "dsm("
- "print_r("
- "var_dump("
- "exit;"

### Whitelist

`/^(docroot|web)\/modules\/custom\/(.*)/`

`/^(docroot|web)\/themes\/custom\/(.*)/`

## PHP CS

Check for Drupal and DrupalPractice.

### Ignore folders

`config/`

`docroot/libraries/`

`docroot/themes/custom/*/components`

`web/libraries/`

`web/themes/custom/*/components`

### Whitelist


`/^(docroot|web)\/modules\/custom\/(.*)/`

`/^(docroot|web)\/themes\/custom\/(.*)/`
