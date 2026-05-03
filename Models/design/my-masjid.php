<?php 
$html = '';
$sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
$prayerNames = $this->getLocalPrayerNames();
$headers = $this->getLocalHeaders();
$prayers = ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'];
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

  :root {
    --bg: #e8e8e8;
    --panel: #d4d4d4;
    --dark: #2b3a4a;
    --teal: #2bbcd4;
    --white: #ffffff;
    --text-main: #2b3a4a;
    --text-light: #7a8a99;
    --highlight-row: #2bbcd4;
    --shadow: rgba(0,0,0,0.12);
    --clock-face: #d0d0d0;
    --clock-dot: #a0a8b0;
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
    justify-content: space-evenly;
    padding: 5vh 2vw;
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
  }

  .masjid-logo {
    width: 6vw;
    height: 6vw;
    max-width: 80px;
    max-height: 80px;
    background: #4a7c59;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .masjid-logo svg { width: 80%; height: 80%; }

  .masjid-info {
    text-align: left;
    width: auto;
  }

  .masjid-info h1 {
    font-size: 2vw;
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
    width: 35vw;
    height: 35vw;
    max-width: 400px;
    max-height: 400px;
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
    justify-content: flex-start;
    padding: 5vh 4vw;
    position: relative;
  }

  /* ── NEXT PRAYER BANNER ── */
  .next-banner {
    background: var(--dark);
    color: var(--white);
    border-radius: 12px;
    padding: 2vh 2vw;
    margin-bottom: 3vh;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .next-banner .label {
    font-size: 2vw;
    font-weight: 400;
    opacity: 0.75;
  }

  .next-banner .countdown {
    font-size: 6vw;
    font-weight: 800;
    color: var(--teal);
    line-height: 1;
    letter-spacing: -2px;
  }

  .next-banner .countdown span {
    font-size: 2vw;
    font-weight: 600;
    letter-spacing: 0;
  }

  .next-banner .website {
    font-size: 1.2vw;
    opacity: 0.5;
    align-self: flex-end;
  }

  /* ── PRAYER TABLE ── */
  .prayer-table {
    width: 100%;
    border-collapse: collapse;
    flex: 1;
  }

  .prayer-table thead tr {
    border-bottom: 2px solid rgba(43,60,74,0.12);
  }

  .prayer-table th {
    font-size: 1.5vw;
    font-weight: 600;
    color: var(--text-main);
    text-align: left;
    padding: 0 0 1vh 0;
  }

  .prayer-table th.ar {
    font-family: 'Tajawal', sans-serif;
    font-size: 1.3vw;
    color: var(--text-light);
    font-weight: 400;
    direction: rtl;
  }

  .prayer-table th.right-align,
  .prayer-table td.right-align { text-align: right; }

  .prayer-table td {
    font-size: 2vw;
    font-weight: 500;
    color: var(--text-main);
    padding: 1.5vh 0;
    border-bottom: 1px solid rgba(43,60,74,0.07);
  }

  .prayer-table td.ar {
    font-family: 'Tajawal', sans-serif;
    font-size: 1.4vw;
    color: var(--text-light);
    direction: rtl;
  }

  .prayer-table td.bold { font-weight: 700; font-size: 2.2vw; }

  /* Highlighted row */
  .prayer-table tr.active td {
    color: var(--teal);
    font-weight: 700;
  }

  .prayer-table tr.active td.name { font-size: 2.2vw; }

  /* Shouruq (dimmed) */
  .prayer-table tr.dimmed td {
    color: var(--text-light);
    font-weight: 400;
  }

  /* ── WATERMARK ── */
  .watermark {
    margin-top: auto;
    text-align: right;
    font-size: 1vw;
    color: var(--text-light);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    padding-top: 1vh;
  }

  .watermark-icon {
    width: 2vw;
    height: 2vw;
    max-width: 24px;
    max-height: 24px;
    background: var(--text-main);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
</style>
</head>
<body>

<div class="screen">

  <!-- LEFT -->
  <div class="left">

    <div class="masjid-header">
      <div class="masjid-logo">
        <svg viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="24" width="36" height="14" rx="2" fill="#d4ead9"/>
          <path d="M21 6 C14 6 8 12 8 18 L8 24 L34 24 L34 18 C34 12 28 6 21 6Z" fill="#d4ead9"/>
          <rect x="18" y="2" width="6" height="8" rx="3" fill="#d4ead9"/>
          <rect x="5" y="18" width="4" height="6" fill="#d4ead9"/>
          <rect x="33" y="18" width="4" height="6" fill="#d4ead9"/>
          <rect x="17" y="30" width="8" height="8" rx="1" fill="#4a7c59"/>
        </svg>
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
    </div>

  </div>

  <!-- RIGHT -->
  <div class="right">

    <!-- Next prayer banner -->
    <div class="next-banner">
      <div>
        <div class="label">Next prayer in</div>
      </div>
      <div class="countdown" id="nextCountdown">--<span>min</span></div>
      <div class="website"><?php echo parse_url(get_bloginfo('url'), PHP_URL_HOST); ?></div>
    </div>

    <!-- Prayer Times Table -->
    <table class="prayer-table">
      <thead>
        <tr>
          <th><?php echo $headers['prayer']; ?></th>
          <th class="ar">صلاة</th>
          <th class="right-align"><?php echo strtoupper($headers['begins']); ?><br><span style="font-family:'Tajawal',sans-serif;font-weight:400;font-size:12px;color:#7a8a99;">أذان</span></th>
          <th class="right-align"><?php echo strtoupper($headers['iqamah']); ?><br><span style="font-family:'Tajawal',sans-serif;font-weight:400;font-size:12px;color:#7a8a99;">إقامة</span></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prayers as $i => $prayer): ?>
        <tr class="<?php echo $i === 0 ? 'active' : ''; ?>">
          <td class="name"><?php echo $prayerNames[$prayer]; ?></td>
          <td class="ar"><?php echo $prayer === 'fajr' ? 'فجر' : ($prayer === 'zuhr' ? 'ظهر' : ($prayer === 'asr' ? 'عصر' : ($prayer === 'maghrib' ? 'مغرب' : 'عشاء'))); ?></td>
          <td class="bold right-align"><?php echo do_shortcode("[{$prayer}_start]"); ?></td>
          <td class="right-align"><?php echo do_shortcode("[{$prayer}_prayer]"); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="dimmed">
          <td class="name"><?php echo $prayerNames[$sunriseOrZawal]; ?></td>
          <td class="ar"><?php echo $sunriseOrZawal === 'zawal' ? 'زوال' : 'شروق'; ?></td>
          <td class="right-align"><?php echo do_shortcode("[{$sunriseOrZawal}]"); ?></td>
          <td class="right-align">–</td>
        </tr>
      </tbody>
    </table>

    <div class="watermark">
      <?php echo parse_url(get_bloginfo('url'), PHP_URL_HOST); ?>
      <div class="watermark-icon">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
          <path d="M7 1C4 1 1 3.5 1 7s3 6 6 6 6-3 6-6-3-6-6-6z" fill="white" opacity="0.8"/>
        </svg>
      </div>
    </div>

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
}
updateClock();
setInterval(updateClock, 1000);
</script>

</body>
</html>