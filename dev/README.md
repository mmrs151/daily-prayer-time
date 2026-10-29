# Local shortcode demo

From the repository root, run `bash dev/setup.sh`. It starts a persistent WordPress and MariaDB site on `http://127.0.0.1:8080/`, activates this checkout of the plugin, imports the supplied 2026 EICT sample timetable, and creates a gallery with a separate page for each of the 23 registered shortcodes plus common variants. The admin login is `dptdemo` / `local-demo-only` and is bound to localhost only.

The digital screen pages use the plugin's full-screen page template. They start with a daily image, a Friday image, a weekend image, and a message for the current weekday. Edit the schedules under Prayer time → Settings → Digital Screen → Slider Settings. The weekday is based on the WordPress site's timezone, set to Europe/London in this demo. The setup skips Freemius's optional first-run connection screen locally so the settings open immediately.

Stop it with `docker compose -p dpt-demo -f dev/compose.yml down`. Add `--volumes` to discard the demo database and start over. Do not use this demo's credentials or sample timetable on a public site.
