[![Sync Status](https://github.com/wp-spaghetti/bedrock-autoloader-mu/actions/workflows/sync.yml/badge.svg)](https://github.com/wp-spaghetti/bedrock-autoloader-mu/actions)
[![Latest Stable Version](https://poser.pugx.org/wp-spaghetti/bedrock-autoloader-mu/v/stable)](https://packagist.org/packages/wp-spaghetti/bedrock-autoloader-mu)
[![Total Downloads](https://poser.pugx.org/wp-spaghetti/bedrock-autoloader-mu/downloads)](https://packagist.org/packages/wp-spaghetti/bedrock-autoloader-mu)
![License](https://img.shields.io/github/license/wp-spaghetti/bedrock-autoloader-mu)

# Bedrock MU-Plugin Autoloader

Self-contained Bedrock autoloader for must-use plugins. This package is automatically synchronized daily from [roots/bedrock](https://github.com/roots/bedrock) and [roots/bedrock-autoloader](https://github.com/roots/bedrock-autoloader).

## Why this package?

The original [roots/bedrock-autoloader](https://github.com/roots/bedrock-autoloader) requires the library to be loaded before the mu-plugin wrapper executes. This creates a chicken-and-egg problem in standard WordPress environments where mu-plugins load before regular plugins.

This package solves that by:
- Combining the Autoloader class and wrapper into a single self-contained file
- Auto-syncing daily from upstream sources
- Installing directly in `mu-plugins` root (not a subdirectory)
- Working out-of-the-box with WordPress `mu-plugins` directory
- Requiring no external dependencies at runtime

## Installation

Install via Composer:
```bash
composer require wp-spaghetti/bedrock-autoloader-mu
```

Add to your `composer.json` installer-paths:
```json
{
    "extra": {
        "installer-paths": {
            "../../public/wp-content/mu-plugins/{$name}/": [
                "type:wordpress-muplugin"
            ]
        }
    }
}
```

**How it works:**
1. Composer installs the package in `mu-plugins/bedrock-autoloader-mu/`
2. A post-install script automatically copies `bedrock-autoloader.php` to `mu-plugins/bedrock-autoloader.php`
3. WordPress loads `mu-plugins/bedrock-autoloader.php` directly
4. The autoloader loads all other mu-plugin subdirectories

## Usage

Once installed, the autoloader will automatically load all subdirectories in `wp-content/mu-plugins/` as plugins.

Example structure after installation:
```
wp-content/
└── mu-plugins/
    ├── bedrock-autoloader.php (copied here by Composer script)
    ├── bedrock-autoloader-mu/
    │   └── bedrock-autoloader.php (original file)
    └── your-mu-plugin/
        └── your-mu-plugin.php (auto-loaded)
```

### Example with Composer
```json
{
    "require": {
        "wp-spaghetti/bedrock-autoloader-mu": "^1.0"
    },
    "extra": {
        "installer-paths": {
            "../../public/wp-content/mu-plugins/{$name}/": [
                "type:wordpress-muplugin"
            ]
        }
    }
}
```

## Auto-Sync

This repository automatically syncs the following files every day at 2 AM UTC:
- [Autoloader.php](https://github.com/roots/bedrock-autoloader/blob/master/src/Autoloader.php) - The autoloader class
- [bedrock-autoloader.php](https://github.com/roots/bedrock/blob/master/web/app/mu-plugins/bedrock-autoloader.php) - The wrapper file

When changes are detected, a new version tag is automatically created with format `v1.0.YYYYMMDD`.

## Contributing

For your contributions please use:

- [Conventional Commits](https://www.conventionalcommits.org)
- [Pull request workflow](https://docs.github.com/en/get-started/exploring-projects-on-github/contributing-to-a-project)

See [CONTRIBUTING](.github/CONTRIBUTING.md) for detailed guidelines.

## Sponsor

[<img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" width="200" alt="Buy Me A Coffee">](https://buymeacoff.ee/frugan)

## Credits

Based on the original work by [Roots](https://roots.io/):
- [roots/bedrock](https://github.com/roots/bedrock)
- [roots/bedrock-autoloader](https://github.com/roots/bedrock-autoloader)

## License

MIT License - See [LICENSE](LICENSE.md) file for details.

Original code © Roots  
Self-contained package © Frugan
