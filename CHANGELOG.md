## [1.1.0](https://github.com/yassinedoghri/php-icons/compare/v1.0.0...v1.1.0) (2024-10-07)

### Internal

- **core-parser:** replace `nikic/PHP-Parser` with `PhpToken` class
  ([2bc0c37](https://github.com/yassinedoghri/php-icons/commit/2bc0c377b664501f48fee74cce2b9efae99c2bcf))

# 1.0.0 (2024-09-28)

### Bug Fixes

- **init:** skip config generation if file already exists
  ([ac5ecc1](https://github.com/yassinedoghri/php-icons/commit/ac5ecc155a8bdd6f3e964905705ded3dab4451c1))

### Features

- add `default_pack` option for allowing better design consistency
  ([09a698e](https://github.com/yassinedoghri/php-icons/commit/09a698ef98e874dd781c73421547a8855f10a41f))
- add defaultIcon and defaultIconPerSet configs
  ([9064d60](https://github.com/yassinedoghri/php-icons/commit/9064d60f66d031181adf11b4328ff923c5c82288))
- add php-icons CLI tool with init and scan commands
  ([7601070](https://github.com/yassinedoghri/php-icons/commit/7601070a7d3b927fd1ac3ceb72157bacda09ddbc))
- download, cache and render svg icons using Iconify's API
  ([5aa4aa5](https://github.com/yassinedoghri/php-icons/commit/5aa4aa5da6ade6aa449238d6e34f0c0efbd8007d))
- use ast visitors to detect icon annotations and functions
  ([d3713f7](https://github.com/yassinedoghri/php-icons/commit/d3713f7a902997912a309de2d594fb61abb9d351))
