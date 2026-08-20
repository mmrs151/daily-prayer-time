# Changelog

## [2026.08.20] - 2026-08-20

### Fixed
- Individual start-time shortcodes (`[fajr_start]`, `[zuhr_start]`, `[asr_start]`, `[maghrib_start]`, `[isha_start]`) now keep showing today's start time until the prayer's Jamat/Iqamah time has passed before switching to tomorrow's time. Previously they rolled over to tomorrow's value the minute the start time passed.

---

## [2026.05.09] - 2026-05-09

### Added
- Add remove slider button to Digital Screen settings - allows users to remove unwanted sliders

### Fixed
- Fix slider option deletion - removed sliders are now properly deleted from database
- Fix malformed CSS in getTableBackgroundStyles
- Fix seamless ticker - duplicate content and animate 0 to -50% for continuous loop

### Enhancement
- GPU acceleration for digital screen animations - improved performance on low-end devices
  - Added `will-change`, `transform: translateZ(0)`, `backface-visibility` to scroll ticker
  - Added `will-change: opacity` to blink, carousel fade, and clock pulse animations

---

## [2026.05.05] - 2026-05-05
- Previous release