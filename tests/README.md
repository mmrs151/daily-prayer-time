# Release checks

Run `bash tests/run.sh` from the repository root. The runner creates a disposable WordPress and MariaDB installation in Docker, activates this plugin, imports the supplied EICT timetable, runs the checks, and removes the containers and database afterward. It never connects to a mosque's site. Set `DPT_WP_VERSION=6.8.3` to run against that WordPress version; CI checks both 6.8.3 and the latest release.

The suite checks all 23 registered shortcodes, common display variants, all three digital screen templates, per-slide weekday scheduling, monthly table rendering, CSV validation and import, selected REST filters, and regressions for SQL quoting, template paths, and logo escaping. Plugin PHP warnings fail the run. GitHub Actions also checks PHP syntax and Composer security advisories.

The Quran CSV files and `vendor/autoload.php` are required release assets and are committed. The locked Composer packages currently point to upstream GitHub archives that return 404, so CI uses the pinned bundled copy and still audits the lockfile for advisories. Include these assets in any distributed plugin ZIP.

This is a release gate, not proof that every configuration, WordPress version, or security issue has been covered. Test a release candidate ZIP on a staging site that matches the oldest supported PHP and WordPress versions before publishing it.
