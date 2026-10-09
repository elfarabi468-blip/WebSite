<?php
// Zex Hub — ana sayfa
session_start();

$DATA_FILE = __DIR__ . '/scripts.json';
if (!file_exists($DATA_FILE)) {
    file_put_contents($DATA_FILE, json_encode([
        ["title" => "Josh Bns", "code" => 'loadstring(game:HttpGet("https://raw.githubusercontent.com/Dayvinksthik/Script/refs/heads/main/Games/JoshBNS-Crack.lua"))()'],
        ["title" => "Shaders", "code" => 'loadstring(game:HttpGet("https://raw.githubusercontent.com/swaggayoung581-sudo/special-computing-machine/refs/heads/main/SAE_HUB_MENU_SKY_ACCESSORY_ANIM_FIXED_FOV_ANIM_COMPACT_SAVE_CONFIG.lua"))()']
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

$scripts = json_decode(file_get_contents($DATA_FILE), true) ?: [];
$isAdmin = !empty($_SESSION['zexhub_admin']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zex Hub</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="topbar">
  <div class="topbar-inner">
    <div class="dots"><i class="dot r"></i><i class="dot y"></i><i class="dot g"></i></div>
    <div class="brand">ZEX<span>HUB</span></div>
    <div class="live"><span class="pulse"></span><span id="onlineCount">4</span> online</div>
    <div class="spacer"></div>
    <a class="btn btn-discord" href="https://discord.gg/NHyqxVSjz" target="_blank" rel="noopener">Discord</a>
    <a class="btn btn-clax" href="https://claxdomaltan.ct.ws" target="_blank" rel="noopener">ClaxSex</a>
    <?php if ($isAdmin): ?>
      <button class="btn btn-ghost" onclick="doLogout()">Çıkış</button>
    <?php endif; ?>
  </div>
</div>

<div class="wrap">
  <div class="hero">
    <button class="btn btn-primary" onclick="openAdd()">+ Script Ekle</button>
    <div class="grow"></div>
    <button class="btn btn-ghost" onclick="openModal('reqOverlay')">Script İsteği Gönder</button>
  </div>

  <div id="scripts">
    <?php if (empty($scripts)): ?>
      <div class="empty">Henüz script yok. Admin girişiyle ilk scripti ekle.</div>
    <?php else: ?>
      <?php foreach ($scripts as $i => $s): ?>
        <div class="card">
          <div class="card-head">
            <div class="card-title"><?= htmlspecialchars($s['title'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="status"><i class="dot2"></i>Online</div>
          </div>
          <pre id="code-<?= $i ?>"><?= htmlspecialchars($s['code'], ENT_QUOTES, 'UTF-8') ?></pre>
          <button class="btn btn-red" onclick="copyCode('code-<?= $i ?>')">Kopyala</button>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- ADIM 1: kullanıcı adı + şifre -->
<div class="overlay" id="authOverlay">
  <div class="modal">
    <h3>Admin Doğrulama</h3>
    <p class="sub">Script eklemek için bilgilerini gir.</p>
    <form method="POST" action="auth.php">
      <div class="err <?= isset($_GET['err']) ? 'show' : '' ?>">Hatalı kullanıcı adı veya şifre.</div>
      <div class="field">
        <label>KULLANICI ADI</label>
        <input name="username" type="text" autocomplete="off" placeholder="username" required>
      </div>
      <div class="field">
        <label>ŞİFRE</label>
        <input name="password" type="password" autocomplete="off" placeholder="••••••••" required>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal('authOverlay')">İptal</button>
        <button type="submit" class="btn btn-primary">Devam</button>
      </div>
    </form>
  </div>
</div>

<!-- ADIM 2: script ekleme (sadece doğrulanmışsa) -->
<?php if ($isAdmin): ?>
<div class="overlay" id="addOverlay">
  <div class="modal">
    <h3>Script Ekle</h3>
    <p class="sub">Başlık ve loadstring kodunu gir.</p>
    <form method="POST" action="add_script.php">
      <div class="field">
        <label>SCRIPT BAŞLIĞI</label>
        <input name="title" type="text" autocomplete="off" placeholder="Josh Bns" required>
      </div>
      <div class="field">
        <label>SCRIPT KODU</label>
        <textarea name="code" placeholder='loadstring(game:HttpGet("https://..."))()' required></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal('addOverlay')">İptal</button>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Script isteği -->
<div class="overlay" id="reqOverlay">
  <div class="modal">
    <h3>Script İsteği</h3>
    <p class="sub">Hangi scripti görmek istiyorsun? Discord'a iletilecek.</p>
    <div class="field">
      <label>SCRIPT ADI / İSTEK</label>
      <textarea id="reqText" placeholder="Örn: Blox Fruits auto farm"></textarea>
    </div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal('reqOverlay')">İptal</button>
      <button class="btn btn-primary" onclick="sendReq()">Gönder</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>
<script>
  // php → js köprüsü
  const IS_ADMIN = <?= $isAdmin ? 'true' : 'false' ?>;
  const HAS_ERR  = <?= isset($_GET['err']) ? 'true' : 'false' ?>;
</script>
<script src="script.js"></script>
</body>
</html>