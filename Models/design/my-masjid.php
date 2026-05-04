<?php 
$html = '';
$sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
$prayerNames = $this->getLocalPrayerNames();
$headers = $this->getLocalHeaders();
$prayers = ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'];
$nextPrayer = $this->dptHelper->getNextPrayer($this->row);
$localTimes = $this->getLocalTimes();

// Get raw times (just H:SS format)
$maghribTime = isset($this->row['maghrib_begins']) ? substr($this->row['maghrib_begins'], 0, 5) : '20:00';
$fajrTime = isset($this->row['fajr_begins']) ? substr($this->row['fajr_begins'], 0, 5) : '04:00';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo get_bloginfo('name'); ?> – Prayer Times</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  /* Light Mode Colors */
  :root {
    --bg: #e8e8e8;
    --panel: #d4d4d4;
    --dark: #2b3a4a;
    --teal: #2bbcd4;
    --white: #ffffff;
    --text-main: #2b3a4a;
    --text-light: #7a8a99;
    --highlight: #ffd700;
    --shadow: rgba(0,0,0,0.12);
    --clock-face: #d0d0d0;
    --clock-dot: #a0a8b0;
  }

  /* Dark Mode Colors */
  .dark-mode {
    --bg: #0a1628;
    --panel: #1a2d42;
    --dark: #1a3a5c;
    --teal: #ffd700;
    --white: #1a2d42;
    --text-main: #e8e8e8;
    --text-light: #a0a8b0;
    --shadow: rgba(0,0,0,0.4);
    --clock-face: #2a3d52;
    --clock-dot: #4a5d6a;
  }

  .dark-mode .prayer-table td,
    .dark-mode .prayer-table th {
    color: var(--text-main); /* already #e8e8e8 in dark mode */
    border-bottom-color: rgba(255,255,255,0.07);
    }

    .dark-mode .prayer-table thead tr {
    border-bottom-color: rgba(255,255,255,0.12);
    }

    .dark-mode .prayer-table td.ar,
    .dark-mode .prayer-table th.ar {
    color: var(--text-light); /* #a0a8b0 in dark mode */
    }

    .dark-mode .prayer-table tr.dimmed td {
    color: var(--text-light);
    }

  /* Override with backend highlight if set */
  .x-board-my-masjid {
    --highlight: var(--dpt-highlight, #ffd700);
  }

  body {
    font-family: 'Outfit', sans-serif;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 0;
  }

  .screen {
    width: 100vw;
    height: 100vh;
    background: var(--bg);
    display: grid;
    grid-template-columns: 1fr 1fr;
    overflow: hidden;
    position: relative;
  }

  /* ── LEFT PANEL ── */
  .left {
    background: var(--panel);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 2vh 2vw;
    position: relative;
  }

  .masjid-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 1vw;
    text-align: left;
    width: 100%;
    margin: 50px 0;
  }

  .masjid-logo {
    width: 6vw;
    height: 6vw;
    max-width: 100%;
    max-height: 100%;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    flex-shrink: 0;
  }

  .masjid-logo svg { width: 80%; height: 80%; }

  .masjid-info {
    text-align: left;
    width: auto;
  }

  .masjid-info h1 {
    font-size: 3vw;
    font-weight: 700;
    color: var(--text-main);
    line-height: 1.1;
  }

  .masjid-info .arabic {
    font-family: 'Tajawal', sans-serif;
    font-size: 1.4vw;
    color: var(--text-light);
    direction: rtl;
    margin-top: 2px;
  }

  .masjid-info .address {
    font-size: 1vw;
    color: var(--text-light);
    margin-top: 3px;
  }

  /* ── ANALOG CLOCK ── */
  .clock-wrap {
    position: relative;
    width: 40vw;
    height: 40vw;
    max-width: 420px;
    max-height: 420px;
    flex-shrink: 0;
  }

  .clock-svg {
    width: 100%;
    height: 100%;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,0.15));
  }

  /* ── DIGITAL TIME ── */
  .digital {
    text-align: center;
  }

  .digital .time {
    font-size: 6vw;
    font-weight: 800;
    color: var(--text-main);
    letter-spacing: 2px;
    line-height: 1;
  }

  .digital .date {
    font-size: 1.4vw;
    font-weight: 400;
    color: var(--text-light);
    margin-top: 6px;
    text-transform: capitalize;
  }

  /* ── RIGHT PANEL ── */
  .right {
    background: var(--bg);
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* banner + table fill height evenly */
    height: 100vh;
    overflow: hidden;
    padding: 3vh 3vw;
    position: relative;
  }

/* ── NEXT PRAYER BANNER ── */
  .next-banner {
    background: var(--dark);
    color: var(--white);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 2vh;
    padding: 1.5vh 2vw; /* add padding so it breathes at all sizes */
    border-radius: 1vw;
  }

  .next-banner .next-name, .dptScTime{
    font-size: clamp(1.5rem, 2vw, 2.5rem);
    font-weight: 700;
  }

  .next-banner .countdown {
    font-size: clamp(3rem, 5vw, 5rem);
    font-weight: 800;
    line-height: 1;
    letter-spacing: -2px;
  }

  /* ── PRAYER TABLE ── */
  .prayer-table {
    width: 100%;
    border-collapse: collapse;
    flex: 1;
    height: 100%; /* lets table rows stretch to fill remaining space */
  }

  .prayer-table thead tr {
    border-bottom: 2px solid rgba(43,60,74,0.12);
    height: calc(100% / 8); /* divide rows equally — adjust 7 to your row count */
  }

  .prayer-table th {
    font-size: clamp(2rem, 2.5vw, 2rem);
    font-weight: 600;
    color: var(--text-main);
    text-align: left;
    padding: 0 0 1vh 0;
  }

  .prayer-table th.ar {
    font-family: 'Tajawal', sans-serif;
    font-size: clamp(2rem, 2.5vw, 2rem);
    color: var(--text-light);
    font-weight: 400;
    direction: rtl;
  }

  .prayer-table th.right-align,
  .prayer-table td.right-align { text-align: right; }

  .prayer-table td {
    font-size: clamp(2rem, 2.5vw, 2rem);
    font-weight: 500;
    padding: 1.2vh 0;
    border-bottom: 1px solid rgba(43,60,74,0.07);
  }

  .prayer-table td.ar {
    font-family: 'Tajawal', sans-serif;
    font-size: clamp(1rem, 2.5vw, 2rem);
    color: var(--text-light);
    text-align: center;
  }

  .prayer-table td.bold { font-weight: 700; font-size: 2.2vw; }

/* Active/Next Prayer Row - color set by UpdateStyles */
  .prayer-table tr.nextPrayer td {
    font-weight: 900;
  }

  /* Dimmed rows */
  .prayer-table tr.dimmed td {
    color: var(--text-light);
    font-weight: 400;
  }

  .x-board-my-masjid td span.dpt_start,
  .x-board-my-masjid td span.dpt_jamah {
    font-size: clamp(2rem, 2.5vw, 2rem) !important;
  }

  .x-board-my-masjid p.hijriDate {
    color: var(--text-main) !important;
  }

  @media (max-width: 768px) {
  .left {
    display: none;
  }

  .screen {
    grid-template-columns: 1fr;
  }

  .timeLeftCountDown, h2.dptScTime{
    font-size: clamp(1.5rem, 2.5vw, 2rem) !important;
  }
}
</style>
</head>
<body>

<div class="screen x-board-my-masjid" 
     data-maghrib="<?php echo $maghribTime; ?>" 
     data-fajr="<?php echo $fajrTime; ?>">

  <!-- LEFT -->
  <div class="left">

    <div class="masjid-header">
      <div class="masjid-logo">
      <?php echo $this->getLogoUrl(); ?>
      </div>
      <div class="masjid-info">
        <h1><?php echo get_bloginfo('name'); ?></h1>
      </div>
    </div>

    <!-- Analog Clock -->
    <div class="clock-wrap">
      <svg class="clock-svg" viewBox="0 0 300 300" id="analogClock">
        <circle cx="150" cy="150" r="140" fill="#d4d4d4" stroke="#c0c0c0" stroke-width="2"/>
        <circle cx="150" cy="22" r="8" fill="#a0a8b0"/>
        <circle cx="278" cy="150" r="8" fill="#a0a8b0"/>
        <circle cx="150" cy="278" r="8" fill="#a0a8b0"/>
        <circle cx="22" cy="150" r="8" fill="#a0a8b0"/>
        <circle cx="224" cy="37" r="5" fill="#b8bec5"/>
        <circle cx="263" cy="76" r="5" fill="#b8bec5"/>
        <circle cx="263" cy="224" r="5" fill="#b8bec5"/>
        <circle cx="224" cy="263" r="5" fill="#b8bec5"/>
        <circle cx="76" cy="263" r="5" fill="#b8bec5"/>
        <circle cx="37" cy="224" r="5" fill="#b8bec5"/>
        <circle cx="37" cy="76" r="5" fill="#b8bec5"/>
        <circle cx="76" cy="37" r="5" fill="#b8bec5"/>
        <line id="hourHand" x1="150" y1="150" x2="150" y2="80" stroke="#6a7a8a" stroke-width="10" stroke-linecap="round" transform="rotate(0, 150, 150)"/>
        <line id="minuteHand" x1="150" y1="150" x2="150" y2="50" stroke="#6a7a8a" stroke-width="7" stroke-linecap="round" transform="rotate(0, 150, 150)"/>
        <line id="secondHand" x1="150" y1="170" x2="150" y2="40" stroke="#e05050" stroke-width="3" stroke-linecap="round" transform="rotate(0, 150, 150)"/>
        <circle cx="150" cy="150" r="10" fill="#5a6a7a"/>
        <circle cx="150" cy="150" r="5" fill="#3a4a5a"/>
      </svg>
    </div>

    <!-- Digital Clock -->
    <div class="digital">
      <div class="time" id="digitalTime">--:--</div>
      <div class="date" id="digitalDate">--</div>
      <p class="hijriDate"><?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()); ?></p>
    </div>

  </div>

  <!-- RIGHT -->
  <div class="right">

    <!-- Next prayer banner -->
    <?php echo $this->getHiddenVariables(); ?>
    <div class="next-banner">
      <div class="next-name highlight-text"><?php echo do_shortcode("[daily_next_prayer]"); ?></div>
      <h2 id="dsNextPrayer" class="countdown highlight-text"></h2>
    </div>

    <!-- Prayer Times Table -->
    <table class="prayer-table">
      <thead>
        <tr>
          <th><?php echo strtoupper($headers['prayer']); ?></th>
          <th class="ar">صلاة</th>
          <th class="right-align"><?php echo strtoupper($headers['begins']); ?><br><span>أذان</span></th>
          <th class="right-align"><?php echo strtoupper($headers['iqamah']); ?><br><span>إقامة</span></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prayers as $prayer): ?>
        <tr class="<?php echo $this->dptHelper->getNextPrayerClass($prayer, $this->row, $prayer === 'fajr'); ?>">
          <td class="name"><?php echo $prayerNames[$prayer]; ?></td>
          <td class="ar"><?php echo $prayer === 'fajr' ? 'فجر' : ($prayer === 'zuhr' ? 'ظهر' : ($prayer === 'asr' ? 'عصر' : ($prayer === 'maghrib' ? 'مغرب' : 'عشاء'))); ?></td>
          <td class="right-align"><?php echo do_shortcode("[{$prayer}_start]"); ?></td>
          <td class="right-align"><?php echo do_shortcode("[{$prayer}_prayer]"); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="dimmed">
          <td class="name"><?php echo $prayerNames[$sunriseOrZawal]; ?></td>
          <td class="ar"><?php echo $sunriseOrZawal === 'zawal' ? 'زوال' : 'شروق'; ?></td>
          <td class="right-align"><?php echo do_shortcode("[{$sunriseOrZawal}]"); ?></td>
          <td class="right-align">–</td>
        </tr>
        <?php if (!empty($headers['jumuah'])): ?>
        <tr class="dimmed <?php echo $this->dptHelper->getNextPrayerClass('jumuah', $this->row); ?>">
          <td class="name"><?php echo stripslashes($headers['jumuah']); ?></td>
          <td class="ar">جمعة</td>
          <td class="right-align" colspan="2"><?php echo $this->getJumuahTimesArray(); ?></td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>
</div>

<script>
function updateClock() {
  const now = new Date();
  const h = now.getHours();
  const m = now.getMinutes();
  const s = now.getSeconds();
  document.getElementById('digitalTime').textContent = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0');
  const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('digitalDate').textContent = days[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
  const secDeg = s * 6;
  const minDeg = m * 6 + s * 0.1;
  const hourDeg = (h % 12) * 30 + m * 0.5;
  document.getElementById('hourHand').setAttribute('transform', 'rotate(' + hourDeg + ', 150, 150)');
  document.getElementById('minuteHand').setAttribute('transform', 'rotate(' + minDeg + ', 150, 150)');
  document.getElementById('secondHand').setAttribute('transform', 'rotate(' + secDeg + ', 150, 150)');
  
  // Dark mode check
  checkDarkMode();
}

// Dark mode: Maghrib to Fajr
function checkDarkMode() {
  const screen = document.querySelector('.screen');
  if (!screen) return;

  const now = new Date();
  const currentMinutes = now.getHours() * 60 + now.getMinutes();

  const maghribParts = (screen.dataset.maghrib || '20:00').split(':');
  const maghribMinutes = parseInt(maghribParts[0]) * 60 + parseInt(maghribParts[1]);

  const fajrParts = (screen.dataset.fajr || '04:00').split(':');
  const fajrMinutes = parseInt(fajrParts[0]) * 60 + parseInt(fajrParts[1]);

  // Dark mode: after Maghrib OR before Fajr (overnight window)
  const darkMode = currentMinutes >= maghribMinutes || currentMinutes < fajrMinutes;

  screen.classList.toggle('dark-mode', darkMode);
}
updateClock();
setInterval(updateClock, 1000);
</script>

</body>
</html>