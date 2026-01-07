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

### Method 1: Via Composer with Auto-Copy Helper (Recommended)

Install the package:
```bash
composer require wp-spaghetti/bedrock-autoloader-mu
```

Add this to your **project root** `composer.json`:
```json
{
    "require": {
        "wp-spaghetti/bedrock-autoloader-mu": "^1.0"
    },
    "scripts": {
        "post-install-cmd": [
            "@copy-bedrock-autoloader-mu"
        ],
        "post-update-cmd": [
            "@copy-bedrock-autoloader-mu"
        ],
        "copy-bedrock-autoloader-mu": [
            "WpSpaghetti\\BedrockAutoloader\\CopyHelper::copy"
        ]
    },
    "extra": {
        "installer-paths": {
            "wp-content/mu-plugins/{$name}/": [
                "type:wordpress-muplugin"
            ]
        }
    }
}
```

The helper class will automatically copy `bedrock-autoloader.php` to `mu-plugins/` root on install and update.

### Method 2: Via Composer with Direct Download

Install the package to manage versions via Composer, then download the file directly:

Add this to your **project root** `composer.json`:
```json
{
    "require": {
        "wp-spaghetti/bedrock-autoloader-mu": "^1.0"
    },
    "scripts": {
        "post-install-cmd": [
            "@copy-bedrock-autoloader-mu"
        ],
        "post-update-cmd": [
            "@copy-bedrock-autoloader-mu"
        ],
        "copy-bedrock-autoloader-mu": [
            "curl -sS https://raw.githubusercontent.com/wp-spaghetti/bedrock-autoloader-mu/main/dist/bedrock-autoloader.php -o wp-content/mu-plugins/bedrock-autoloader.php"
        ]
    },
    "extra": {
        "installer-paths": {
            "wp-content/mu-plugins/{$name}/": [
                "type:wordpress-muplugin"
            ]
        }
    }
}
```

> **Note:** Adjust the path `wp-content/mu-plugins/bedrock-autoloader.php` according to your project structure.

## Usage

Once installed, the autoloader will automatically load all subdirectories in `wp-content/mu-plugins/` as plugins.

Example structure after installation:
```
wp-content/
└── mu-plugins/
    ├── bedrock-autoloader.php (← main autoloader file, copied from dist/)
    ├── bedrock-autoloader-mu/
    │   ├── dist/
    │   │   └── bedrock-autoloader.php (← source template)
    │   ├── src/
    │   │   └── CopyHelper.php (← helper class)
    │   └── composer.json
    └── your-mu-plugin/
        └── your-mu-plugin.php (← auto-loaded)
```

## How It Works

1. Composer installs the package in `mu-plugins/bedrock-autoloader-mu/`
2. Your post-install script copies `dist/bedrock-autoloader.php` to `mu-plugins/` root
3. WordPress loads `mu-plugins/bedrock-autoloader.php` directly
4. The autoloader loads all other mu-plugin subdirectories

## Auto-Sync

This repository automatically syncs the following files every day at 2 AM UTC:
- [Autoloader.php](https://github.com/roots/bedrock-autoloader/blob/master/src/Autoloader.php) - The autoloader class
- [bedrock-autoloader.php](https://github.com/roots/bedrock/blob/master/web/app/mu-plugins/bedrock-autoloader.php) - The wrapper file

The combined file is generated and saved in the `dist/` directory to prevent it from being autoloaded by itself.

When changes are detected, a new version tag is automatically created with format `v1.0.YYYYMMDD`.

## Troubleshooting

### File not copied to mu-plugins root

Make sure:
1. You've added the scripts to your **project root** `composer.json` (not the package's composer.json)
2. Your `installer-paths` correctly points to your mu-plugins directory
3. The helper class can write to the mu-plugins directory (check permissions)

### Different project structure

If your project has a custom structure, adjust the paths in your scripts. The helper class copies the file relative to the vendor directory, so you may need to use a custom shell script instead:

```json
"copy-bedrock-autoloader-mu": [
    "cp -f custom/path/to/vendor/wp-spaghetti/bedrock-autoloader-mu/dist/bedrock-autoloader.php custom/path/to/mu-plugins/"
]
```

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
