<?php
$nama    = htmlspecialchars($_GET['nama']    ?? '');
$tanggal = htmlspecialchars($_GET['tanggal'] ?? '');
$waktu   = htmlspecialchars($_GET['waktu']   ?? '');

// DB Connection — shared with JS.php
$conn = mysqli_connect('localhost','root','','db_senja');
$menuItems   = [];
$tableStatus = []; // ['C1' => 1, 'C2' => 0, ...]

if($conn){
    // ── 1. Load menu items ───────────────────────────
    $res = mysqli_query($conn, "SELECT * FROM menus ORDER BY category, name");
    $catMap = [
        'Signature'       => 'coffee',
        'Recommendations' => 'coffee',
        'Manual Brew'     => 'brew',
        'Non-Coffee'      => 'noncoffee',
        'Pastries'        => 'pastry',
        'Bites'           => 'bites',
    ];
    $idx = 0;
    while($row = mysqli_fetch_assoc($res)){
        $cat = $catMap[$row['category']] ?? 'other';
        $priceStr = strtolower(trim($row['price']));
        $priceRaw = str_ends_with($priceStr,'k')
            ? (int)rtrim($priceStr,'k') * 1000
            : (int)preg_replace('/[^0-9]/','',$priceStr);
        $menuItems[] = [
            'id'    => 'db'.$idx++,
            'cat'   => $cat,
            'name'  => $row['name'],
            'desc'  => $row['description'] ?? '',
            'price' => $priceRaw,
            'img'   => $row['image_url'] ? '../'.$row['image_url'] : ''
        ];
    }

    // ── 2. Load table availability from cafe_tables ──
    $tres = mysqli_query($conn, "SELECT table_id, is_available FROM cafe_tables");
    if($tres){
        while($trow = mysqli_fetch_assoc($tres)){
            $tableStatus[$trow['table_id']] = (int)$trow['is_available'];
        }
    }

    mysqli_close($conn);
}

// ── 3. Server-side Validation (H-1 & Operating Hours) ──
if ($tanggal && $waktu) {
    $now = new DateTIme();
    $resDate = new DateTime($tanggal . ' ' . $waktu);
    $tomorrow = (new DateTime())->modify('+1 day')->setTime(0,0,0);
    
    $dayOfWeek = (int)$resDate->format('w'); // 0: Sun, 6: Sat
    $hour = (int)$resDate->format('H');
    
    $isValidTime = false;
    $opHours = "";
    if ($dayOfWeek === 0 || $dayOfWeek === 6) {
        if ($hour >= 9 && $hour < 23) $isValidTime = true;
        $opHours = "09:00 - 23:00 (Akhir Pekan)";
    } else {
        if ($hour >= 8 && $hour < 22) $isValidTime = true;
        $opHours = "08:00 - 22:00 (Hari Kerja)";
    }

    if ($resDate < $tomorrow || !$isValidTime) {
        $msg = ($resDate < $tomorrow) 
            ? "Reservasi minimal dilakukan H-1." 
            : "Jam operasional kami pada hari tersebut adalah $opHours.";
        echo "<script>alert('$msg'); window.location.href='../Senja-Kopi.php';</script>";
        exit;
    }
}

// Helper: is a table taken? Falls back to 'available' if not in DB yet.
function isTaken(string $id): bool {
    global $tableStatus;
    // If not in DB at all, treat as available
    return isset($tableStatus[$id]) && $tableStatus[$id] === 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Meja — Senja Coffee</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap');
*{box-sizing:border-box;margin:0;padding:0}
body{background:#1a1612;font-family:'Poppins',sans-serif;min-height:100vh}

/* HEADER */
.header{
  background:linear-gradient(135deg,#1a1612 0%,#2c2218 50%,#1a1612 100%);
  color:#fff;padding:1rem 2rem;
  display:flex;align-items:center;justify-content:space-between;
  border-bottom:2px solid #C5A059;
  box-shadow:0 4px 30px rgba(0,0,0,.5);
}
.brand{font-family:'Playfair Display',serif;font-size:1.5rem;color:#C5A059;display:flex;align-items:center;gap:.5rem}
.brand-sub{font-size:.7rem;font-weight:300;color:#a08060;letter-spacing:2px;text-transform:uppercase;display:block;margin-top:-4px}
.binfo{font-size:.78rem;opacity:.85;text-align:right;line-height:1.6}
.binfo strong{color:#C5A059;font-weight:600}
.binfo-chip{background:rgba(197,160,89,.15);border:1px solid rgba(197,160,89,.3);border-radius:20px;padding:2px 10px;display:inline-block;margin-top:3px}

/* LEGEND */
.legend{
  display:flex;gap:1.5rem;flex-wrap:wrap;justify-content:center;
  padding:.9rem 2rem;
  background:rgba(255,255,255,.04);
  backdrop-filter:blur(10px);
  border-bottom:1px solid rgba(255,255,255,.08);
  font-size:.75rem;color:#ccc;
}
.ld{display:flex;align-items:center;gap:6px}
.dot{width:14px;height:14px;border-radius:3px;box-shadow:0 2px 6px rgba(0,0,0,.3)}
.d-avail{background:#2ecc71;box-shadow:0 0 8px rgba(46,204,113,.5)}
.d-taken{background:#666}
.d-sel{background:#C5A059;box-shadow:0 0 8px rgba(197,160,89,.5)}
.legend-sep{width:1px;height:16px;background:rgba(255,255,255,.15);align-self:center}

/* PAGE SUBTITLE */
.page-sub{
  text-align:center;padding:.6rem;
  background:rgba(197,160,89,.08);
  color:#C5A059;font-size:.72rem;font-weight:500;letter-spacing:1.5px;text-transform:uppercase;
  border-bottom:1px solid rgba(197,160,89,.2);
}

/* FLOORPLAN CANVAS */
.fp-wrap{display:flex;justify-content:center;padding:2rem 1rem 8rem;overflow-x:auto;background:#1a1612}
.floorplan{
  position:relative;
  width:820px;height:600px;
  background-color:#2a2018;
  background-image: radial-gradient(rgba(197,160,89,0.08) 1px, transparent 0);
  background-size: 20px 20px;
  border:3px solid #5a4a3a;
  border-radius:12px;
  flex-shrink:0;
  box-shadow:0 30px 80px rgba(0,0,0,.8), inset 0 0 100px rgba(0,0,0,.4);
}

/* WALLS */
.wall{
  position:absolute;
  background:linear-gradient(145deg,#5d4037,#3e2723);
  box-shadow:2px 2px 8px rgba(0,0,0,0.6);
  border:1px solid rgba(197,160,89,0.15);
}
.window-mark{
  position:absolute;
  background:rgba(135,206,235,0.25);
  border:2px solid #87ceeb;
  box-shadow:0 0 15px rgba(135,206,235,0.4), inset 0 0 5px rgba(135,206,235,0.3);
  backdrop-filter:blur(2px);
}
.door-mark{
  position:absolute;background:rgba(197,160,89,0.1);
  border:1px dashed #C5A059;
  display:flex;align-items:center;justify-content:center;
  font-size:.55rem;color:#C5A059;font-weight:700;letter-spacing:1px;
}

/* ZONE FILLS */
.zone{position:absolute;border-radius:12px;box-shadow:inset 0 0 20px rgba(0,0,0,0.2)}
.z-outdoor{background:rgba(46,139,87,.08);border:1px solid rgba(46,139,87,.15)}
.z-window{background:rgba(100,180,220,.08);border:1px solid rgba(100,180,220,.15)}
.z-bar{background:rgba(160,90,200,.06);border:1px solid rgba(160,90,200,.12)}
.z-indoor{background:rgba(197,160,89,.05);border:1px solid rgba(197,160,89,.1)}
.z-service{background:rgba(0,0,0,.2);border:1px dashed rgba(255,255,255,.1)}

/* ZONE LABELS */
.zlabel{
  position:absolute;font-size:.6rem;font-weight:700;text-transform:uppercase;
  letter-spacing:1px;color:rgba(255,255,255,0.4);
  pointer-events:none;
}

/* BAR COUNTER */
.bar-counter{
  position:absolute;
  background:linear-gradient(145deg,#3e2723,#1b1311);
  border-radius:12px;display:flex;align-items:center;justify-content:center;
  color:#C5A059;font-size:.65rem;font-weight:700;letter-spacing:3px;
  box-shadow:0 10px 30px rgba(0,0,0,0.6), inset 0 1px 2px rgba(255,255,255,0.1);
  border:1.5px solid #C5A059;
}

/* TABLE UNITS */
.tbl{position:absolute;cursor:pointer;display:flex;flex-direction:column;align-items:center;gap:2px;transition:transform .2s,filter .2s}
.tbl.taken{cursor:not-allowed;opacity:.5;filter:grayscale(1)}
.tbl:not(.taken):hover{transform:scale(1.12);filter:brightness(1.1)}
.tbl-top{
  background:linear-gradient(135deg,#8B6914,#a07820);
  border:2px solid #c4a030;
  border-radius:6px;display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:.54rem;font-weight:700;
  box-shadow:0 3px 10px rgba(0,0,0,.4),inset 0 1px 0 rgba(255,255,255,.15);
  transition:border-color .2s,box-shadow .2s;
  text-shadow:0 1px 2px rgba(0,0,0,.5);
}
.tbl.selected .tbl-top{border-color:#C5A059;box-shadow:0 0 0 3px rgba(197,160,89,.5),0 4px 15px rgba(197,160,89,.4)}
.seats-row{display:flex;gap:2px}
.seat{
  border-radius:3px;
  background:linear-gradient(135deg,#2ecc71,#27ae60);
  border:1px solid rgba(255,255,255,.2);
  box-shadow:0 2px 4px rgba(0,0,0,.3);
  transition:background .2s;
}
.tbl.taken .seat{background:#555;box-shadow:none}
.tbl.selected .seat{background:linear-gradient(135deg,#C5A059,#d4a84b);box-shadow:0 2px 6px rgba(197,160,89,.4)}
.tbl:not(.taken):hover .seat{background:linear-gradient(135deg,#1abc9c,#16a085)}
.tbl-lbl{font-size:.5rem;color:rgba(255,255,255,.4);font-weight:600;margin-top:1px;letter-spacing:.5px}

/* BAR STOOLS */
.stool-unit{position:absolute;cursor:pointer;display:flex;flex-direction:column-reverse;align-items:center;transition:transform 0.2s}
.stool-unit:hover{transform:scale(1.15)}
.stool-unit.taken{cursor:not-allowed;opacity:.3;filter:grayscale(1)}
.stool-unit.free-seat{cursor:default;transform:none !important}
.stool-unit.free-seat .stool-circle{
  background:linear-gradient(135deg, #3498db, #2980b9);
  border:1px solid #5dade2;
  box-shadow:0 0 10px rgba(52,152,219,0.4);
}
.stool-unit.free-seat .stool-lbl{color:#5dade2;font-weight:600}
.stool-circle{
  width:16px;height:16px;border-radius:50%;
  background:radial-gradient(circle at 35% 35%,#4ade80,#166534);
  border:2px solid rgba(255,255,255,0.4);
  box-shadow:0 4px 10px rgba(0,0,0,0.5);
  transition:all .2s;
}
.stool-unit.stool-sel .stool-circle{
  background:radial-gradient(circle at 35% 35%,#fbbf24,#92400e);
  border-color:#fff;
  box-shadow:0 0 15px rgba(251,191,36,0.6);
}
.stool-lbl{font-size:.5rem;color:rgba(255,255,255,0.8);text-align:center;margin-bottom:3px;font-weight:700;text-shadow:0 2px 4px #000;pointer-events:none}


/* CONFIRM BAR */
.cbar{
  position:fixed;bottom:0;left:0;right:0;
  background:linear-gradient(135deg,#1a1612,#2c2015);
  color:#fff;padding:1rem 2rem;
  display:flex;align-items:center;justify-content:space-between;
  z-index:100;transform:translateY(100%);
  transition:transform .45s cubic-bezier(.34,1.56,.64,1);
  border-top:2px solid rgba(197,160,89,.4);
  box-shadow:0 -10px 40px rgba(0,0,0,.5);
  backdrop-filter:blur(20px);
}
.cbar.show{transform:translateY(0)}
.cbar .info{font-size:.85rem;display:flex;gap:1.5rem;align-items:center}
.info-chip{background:rgba(197,160,89,.15);border:1px solid rgba(197,160,89,.3);border-radius:15px;padding:3px 12px;font-size:.78rem}
.info-chip span{color:#C5A059;font-weight:600}
.btn-ok{
  background:linear-gradient(135deg,#C5A059,#d4a84b);
  color:#1a1612;border:none;padding:10px 28px;border-radius:25px;
  font-weight:700;cursor:pointer;font-family:'Poppins',sans-serif;
  transition:all .2s;box-shadow:0 4px 15px rgba(197,160,89,.4);
  font-size:.9rem;letter-spacing:.5px;
}
.btn-ok:hover{background:linear-gradient(135deg,#d4b469,#e8c060);transform:scale(1.05);box-shadow:0 6px 20px rgba(197,160,89,.6)}

/* MODAL */
.mbg{
  display:none;position:fixed;inset:0;
  background:rgba(0,0,0,.75);
  backdrop-filter:blur(8px);
  z-index:200;justify-content:center;align-items:center;
}
.mbg.open{display:flex}
.mbox{
  background:linear-gradient(145deg,#2a2018,#1e1810);
  border:1px solid rgba(197,160,89,.3);
  border-radius:20px;padding:2.5rem;max-width:420px;width:90%;
  text-align:center;
  animation:popIn .35s cubic-bezier(.34,1.56,.64,1);
  box-shadow:0 30px 80px rgba(0,0,0,.7),0 0 0 1px rgba(197,160,89,.1);
  color:#fff;
}
@keyframes popIn{from{transform:scale(.7);opacity:0}to{transform:scale(1);opacity:1}}
.mbox h3{font-family:'Playfair Display',serif;font-size:1.5rem;color:#C5A059;margin-bottom:.3rem}
.mbox .sub{color:rgba(255,255,255,.4);font-size:.78rem;margin-bottom:.2rem}
.dgrid{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:1rem;margin:.9rem 0;text-align:left;font-size:.83rem}
.drow{display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.06);align-items:center}
.drow:last-child{border:none}
.drow span{color:rgba(255,255,255,.5)}
.drow strong{color:#C5A059;font-weight:600}
.btn-fin{
  background:linear-gradient(135deg,#C5A059,#d4a84b);
  color:#1a1612;border:none;padding:12px;border-radius:25px;
  font-weight:700;cursor:pointer;width:100%;font-size:.95rem;
  font-family:'Poppins',sans-serif;
  box-shadow:0 4px 20px rgba(197,160,89,.4);
  transition:all .2s;margin-top:.5rem;
}
.btn-fin:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(197,160,89,.5)}
.bk-link{display:block;margin-top:.8rem;color:rgba(255,255,255,.3);font-size:.75rem;text-decoration:none;cursor:pointer;transition:color .2s}
.bk-link:hover{color:rgba(255,255,255,.6)}

/* STEP WIZARD */
.step-bar{display:flex;align-items:center;justify-content:center;padding:.7rem 2rem;gap:0;background:rgba(255,255,255,.03);border-bottom:1px solid rgba(255,255,255,.07)}
.step{display:flex;align-items:center;gap:.5rem;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.2);letter-spacing:.5px;transition:color .3s}
.step.active{color:#C5A059}
.step.done{color:rgba(197,160,89,.45)}
.step-num{width:26px;height:26px;border-radius:50%;border:2px solid currentColor;display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0;transition:all .3s}
.step.active .step-num{background:#C5A059;color:#1a1612;border-color:#C5A059}
.step.done .step-num{background:rgba(197,160,89,.2)}
.step-line{width:55px;height:2px;background:rgba(255,255,255,.08);margin:0 .5rem;flex-shrink:0;transition:background .3s}
.step-line.done{background:rgba(197,160,89,.4)}

/* STEP 2 */
#step2{display:none;background:#1a1612;min-height:calc(100vh - 120px);padding:2rem 1rem 7rem}
.menu-order-wrap{max-width:900px;margin:0 auto}
.menu-order-wrap h2{font-family:'Playfair Display',serif;color:#C5A059;font-size:1.4rem;margin-bottom:.3rem}
.menu-order-wrap .sub{color:rgba(255,255,255,.4);font-size:.78rem;margin-bottom:1.5rem}
.menu-cats{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.5rem}
.cat-btn{padding:5px 14px;border-radius:15px;border:1px solid rgba(197,160,89,.3);background:transparent;color:rgba(255,255,255,.5);font-size:.72rem;font-weight:600;cursor:pointer;transition:all .2s;font-family:'Poppins',sans-serif}
.cat-btn.active{background:#C5A059;color:#1a1612;border-color:#C5A059}
.menu-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem}
.menu-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:1rem 1.2rem;display:flex;justify-content:space-between;align-items:center;transition:border-color .2s}
.menu-card:hover{border-color:rgba(197,160,89,.3)}
.menu-card .mname{font-size:.88rem;font-weight:600;color:#fff;margin-bottom:2px}
.menu-card .mdesc{font-size:.7rem;color:rgba(255,255,255,.35)}
.menu-card .mprice{font-size:.82rem;color:#C5A059;font-weight:700;margin-top:4px}
.qty-ctrl{display:flex;align-items:center;gap:6px;flex-shrink:0}
.qty-btn{width:26px;height:26px;border-radius:50%;border:1px solid rgba(197,160,89,.4);background:transparent;color:#C5A059;font-size:1rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;font-family:'Poppins',sans-serif}
.qty-btn:hover{background:#C5A059;color:#1a1612}
.qty-num{font-size:.85rem;color:#fff;font-weight:600;min-width:18px;text-align:center}
.m-img{width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid rgba(197,160,89,.4);margin-right:12px;box-shadow:0 4px 10px rgba(0,0,0,.3)}
.m-content{display:flex;align-items:center;flex-grow:1}
.order-bar{position:fixed;bottom:0;left:0;right:0;background:linear-gradient(135deg,#1a1612,#2c2015);border-top:2px solid rgba(197,160,89,.4);padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;box-shadow:0 -10px 40px rgba(0,0,0,.5);z-index:100}
.order-bar .total-info{font-size:.85rem;color:rgba(255,255,255,.6)}
.order-bar .total-price{font-size:1.1rem;color:#C5A059;font-weight:700;margin-top:2px}
.skip-link{font-size:.72rem;color:rgba(255,255,255,.25);cursor:pointer;text-decoration:underline;margin-right:1rem}
.skip-link:hover{color:rgba(255,255,255,.5)}

/* STEP 3 */
#step3{display:none;background:#1a1612;min-height:calc(100vh - 120px);padding:2rem 1rem}
.confirm-wrap{max-width:600px;margin:0 auto}
.confirm-card{background:linear-gradient(145deg,#2a2018,#1e1810);border:1px solid rgba(197,160,89,.25);border-radius:18px;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,.4)}
.confirm-card h2{font-family:'Playfair Display',serif;color:#C5A059;font-size:1.5rem;margin-bottom:.3rem}
.confirm-section{margin-top:1.5rem}
.confirm-section h6{font-size:.62rem;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,.3);font-weight:700;margin-bottom:.7rem;padding-bottom:.4rem;border-bottom:1px solid rgba(255,255,255,.06)}
.info-row{display:flex;justify-content:space-between;padding:5px 0;font-size:.83rem;color:rgba(255,255,255,.7)}
.info-row strong{color:#fff;font-weight:600}
.order-list-item{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:.82rem;border-bottom:1px solid rgba(255,255,255,.05)}
.order-list-item:last-child{border:none}
.order-list-item .iname{color:rgba(255,255,255,.75)}
.order-list-item .iqty{color:rgba(255,255,255,.35);font-size:.72rem}
.order-list-item .iamt{color:#C5A059;font-weight:600}
.total-line{display:flex;justify-content:space-between;margin-top:1rem;padding-top:1rem;border-top:2px solid rgba(197,160,89,.2);font-weight:700;font-size:.95rem;color:#fff}
.total-line span:last-child{color:#C5A059}
.pay-notice{margin-top:1.5rem;background:rgba(46,204,113,.08);border:1px solid rgba(46,204,113,.2);border-radius:12px;padding:1rem 1.2rem;display:flex;gap:.8rem;align-items:flex-start}
.pay-notice .icon{font-size:1.5rem;flex-shrink:0}
.pay-notice .ptitle{font-size:.82rem;font-weight:700;color:#2ecc71;margin-bottom:3px}
.pay-notice .pdesc{font-size:.75rem;color:rgba(255,255,255,.4);line-height:1.5}
.btn-reserve{width:100%;margin-top:1.5rem;background:linear-gradient(135deg,#C5A059,#d4a84b);color:#1a1612;border:none;padding:14px;border-radius:25px;font-size:1rem;font-weight:700;cursor:pointer;font-family:'Poppins',sans-serif;box-shadow:0 6px 25px rgba(197,160,89,.4);transition:all .2s;letter-spacing:.5px}
.btn-reserve:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(197,160,89,.5)}
.btn-back{width:100%;margin-top:.7rem;background:transparent;color:rgba(255,255,255,.3);border:1px solid rgba(255,255,255,.1);padding:10px;border-radius:25px;font-size:.82rem;cursor:pointer;font-family:'Poppins',sans-serif;transition:all .2s}
.btn-back:hover{color:rgba(255,255,255,.6);border-color:rgba(255,255,255,.25)}
</style>
</head>
<body>

<div class="header">
  <div>
    <div class="brand">☕ Senja Coffee</div>
    <span class="brand-sub">Seat Selection Floor Plan</span>
  </div>
  <div class="binfo">
    <div>Reservasi untuk: <strong><?= $nama ?: 'Tamu' ?></strong></div>
    <div class="binfo-chip">
      📅 <strong><?= $tanggal ? date('d M Y', strtotime($tanggal)) : '-' ?></strong> 
      &nbsp; 🕒 <strong><?= $waktu ?: '-' ?></strong>
    </div>
  </div>
</div>

<div class="legend">
  <div class="ld"><div class="dot d-avail"></div> Tersedia</div>
  <div class="legend-sep"></div>
  <div class="ld"><div class="dot d-taken"></div> Terisi</div>
  <div class="legend-sep"></div>
  <div class="ld"><div class="dot d-sel"></div> Dipilih</div>
  <div class="legend-sep"></div>
  <div class="ld"><div class="dot" style="background:#3498db;box-shadow:0 0 8px rgba(52,152,219,.5)"></div> Free Seat</div>
  <div class="legend-sep"></div>
  <div class="ld">🪟 Jendela</div>
  <div class="ld">🌿 Outdoor</div>
  <div class="ld">🏠 Indoor</div>
  <div class="ld">🍸 Bar</div>
</div>
<div class="step-bar">
  <div class="step active" id="s1"><div class="step-num">1</div> Pilih Meja</div>
  <div class="step-line" id="sl1"></div>
  <div class="step" id="s2"><div class="step-num">2</div> Pilih Menu</div>
  <div class="step-line" id="sl2"></div>
  <div class="step" id="s3"><div class="step-num">3</div> Konfirmasi</div>
</div>
<div class="page-sub">Klik meja atau kursi yang tersedia untuk memilih tempat duduk Anda</div>

<div id="step1-content">
<div class="fp-wrap">
<div class="floorplan" id="fp">

  <!-- ZONE BACKGROUNDS -->
  <div class="zone z-outdoor"   style="left:0;top:0;width:200px;height:600px"></div>
  <div class="zone z-window"    style="left:200px;top:0;width:240px;height:300px"></div>
  <div class="zone z-bar"       style="left:200px;top:300px;width:240px;height:300px"></div>
  <div class="zone z-indoor"    style="left:440px;top:0;width:380px;height:600px"></div>
  
  <!-- SERVICE ZONES -->
  <div class="zone z-service"   style="left:10px;top:480px;width:180px;height:110px"></div>
  <div class="zone z-service"   style="left:205px;top:380px;width:230px;height:210px"></div>
  <div class="zone z-service"   style="left:440px;top:480px;width:100px;height:110px"></div>
  <div class="zone z-indoor"    style="left:540px;top:480px;width:270px;height:110px;opacity:.6"></div>

  <!-- ZONE LABELS -->
  <div class="zlabel" style="left:8px;top:8px">Outdoor</div>
  <div class="zlabel" style="left:208px;top:8px">Front</div>
  <div class="zlabel" style="left:448px;top:8px">Back</div>
  <div class="zlabel" style="left:212px;top:385px;font-size:.5rem">Dapur / Kitchen</div>
  <div class="zlabel" style="left:15px;top:485px;font-size:.5rem">Toilet</div>
  <div class="zlabel" style="left:445px;top:485px;font-size:.5rem">Toilet</div>
  <div class="zlabel" style="left:548px;top:485px;font-size:.5rem">Stage Area</div>

  <!-- OUTER WALLS -->
  <div class="wall" style="left:0;top:0;width:820px;height:5px"></div>
  <div class="wall" style="left:0;top:595px;width:820px;height:5px"></div>
  <div class="wall" style="left:0;top:0;width:5px;height:600px"></div>
  <div class="wall" style="left:815px;top:0;width:5px;height:600px"></div>

  <!-- INDOOR DIVIDER WALL -->
  <div class="wall" style="left:440px;top:0;width:4px;height:220px"></div>
  <div class="wall" style="left:440px;top:280px;width:4px;height:315px"></div>
  
  <!-- INDOOR SERVICE DIVIDER -->
  
  
  <!-- KITCHEN ENCLOSURE -->
  <div class="wall" style="left:200px;top:375px;width:188px;height:4px"></div>
  <div class="wall" style="left:200px;top:375px;width:4px;height:220px"></div>
  <div class="wall" style="left:200px;top:595px;width:240px;height:4px"></div>
  <div class="door-mark" style="left:388px;top:375px;width:52px;height:4px">🚪</div>
  
  <!-- INDOOR SERVICE WALLS & DOORS -->
  <div class="wall" style="left:440px;top:475px;width:30px;height:4px"></div>
  <div class="door-mark" style="left:470px;top:475px;width:40px;height:4px">🚪</div>
  <div class="wall" style="left:510px;top:475px;width:305px;height:4px"></div>
  <div class="wall" style="left:540px;top:475px;width:4px;height:120px"></div>

  <!-- OUTDOOR TOILET ENCLOSURE & DOORS -->
  <div class="wall" style="left:0px;top:475px;width:125px;height:4px"></div>
  <div class="door-mark" style="left:125px;top:475px;width:40px;height:4px">🚪</div>
  <div class="wall" style="left:165px;top:475px;width:35px;height:4px"></div>
  <div class="wall" style="left:196px;top:475px;width:4px;height:120px"></div>
  
  <!-- Door gap at 220–280 -->
  <div class="door-mark" style="left:441px;top:220px;width:3px;height:60px">⬅</div>

  <!-- DIVIDER WALL (OUTDOOR to BAR) -->
  <div class="wall" style="left:200px;top:0;width:5px;height:600px"></div>

  <!-- WINDOW MARKS -->
  <div class="window-mark" style="left:205px;top:0;width:230px;height:6px;background:rgba(52,152,219,.3)"></div>
  <div class="window-mark" style="left:550px;top:0;width:200px;height:6px;background:rgba(52,152,219,.3)"></div>
  <!-- Move outdoor window to Bar wall (Shortened to avoid stool) -->
  <div class="window-mark" style="left:200px;top:80px;width:6px;height:120px;background:rgba(52,152,219,.5)"></div>
  <!-- Indoor Right Window -->
  <div class="window-mark" style="left:814px;top:100px;width:6px;height:200px;background:rgba(52,152,219,.3)"></div>

  <!-- MAIN ENTRANCE DOOR (Repositioned to avoid F3) -->
  <div class="door-mark" style="left:200px;top:248px;width:6px;height:55px"></div>


  <!-- BAR COUNTER -->
  <div class="bar-counter" style="left:208px;top:340px;width:180px;height:35px">
    ▬ BAR COUNTER ▬
  </div>

  <!-- ═══ OUTDOOR TABLES (C1–C6 couple, F1–F2 four) ═══ -->
  <?php
  $tables = [
    // Outdoor couple
    ['id'=>'C1', 'x'=>18,  'y'=>30,  'type'=>'couple','zone'=>'Outdoor'],
    ['id'=>'C2', 'x'=>18,  'y'=>90,  'type'=>'couple','zone'=>'Outdoor'],
    ['id'=>'C3', 'x'=>18,  'y'=>160, 'type'=>'couple','zone'=>'Outdoor'],
    ['id'=>'C4', 'x'=>100, 'y'=>30,  'type'=>'couple','zone'=>'Outdoor'],
    ['id'=>'C5', 'x'=>100, 'y'=>90,  'type'=>'couple','zone'=>'Outdoor'],
    ['id'=>'C6', 'x'=>100, 'y'=>160, 'type'=>'couple','zone'=>'Outdoor'],
    // Outdoor four
    ['id'=>'F1', 'x'=>15,  'y'=>260, 'type'=>'four',  'zone'=>'Outdoor'],
    ['id'=>'F2', 'x'=>100, 'y'=>260, 'type'=>'four',  'zone'=>'Outdoor'],
    // Front couple
    ['id'=>'C7', 'x'=>225, 'y'=>30,  'type'=>'couple','zone'=>'Front'],
    ['id'=>'C8', 'x'=>290, 'y'=>30,  'type'=>'couple','zone'=>'Front'],
    ['id'=>'C9', 'x'=>355, 'y'=>30,  'type'=>'couple','zone'=>'Front'],
    ['id'=>'C10','x'=>225, 'y'=>110, 'type'=>'couple','zone'=>'Front'],
    ['id'=>'C11','x'=>290, 'y'=>110, 'type'=>'couple','zone'=>'Front'],
    ['id'=>'C12','x'=>355, 'y'=>110, 'type'=>'couple','zone'=>'Front'],
    // Front four
    ['id'=>'F3', 'x'=>230, 'y'=>195, 'type'=>'four',  'zone'=>'Front'],
    ['id'=>'F4', 'x'=>340, 'y'=>195, 'type'=>'four',  'zone'=>'Front'],
    // Back couple
    ['id'=>'C13','x'=>475, 'y'=>30,  'type'=>'couple','zone'=>'Back'],
    ['id'=>'C14','x'=>555, 'y'=>30,  'type'=>'couple','zone'=>'Back'],
    ['id'=>'C15','x'=>635, 'y'=>30,  'type'=>'couple','zone'=>'Back'],
    ['id'=>'C16','x'=>715, 'y'=>30,  'type'=>'couple','zone'=>'Back'],
    ['id'=>'C17','x'=>475, 'y'=>110, 'type'=>'couple','zone'=>'Back'],
    ['id'=>'C18','x'=>555, 'y'=>110, 'type'=>'couple','zone'=>'Back'],
    ['id'=>'C19','x'=>635, 'y'=>110, 'type'=>'couple','zone'=>'Back'],
    ['id'=>'C20','x'=>715, 'y'=>110, 'type'=>'couple','zone'=>'Back'],
    // Back four
    ['id'=>'F5', 'x'=>475, 'y'=>210, 'type'=>'four',  'zone'=>'Back'],
    ['id'=>'F6', 'x'=>585, 'y'=>210, 'type'=>'four',  'zone'=>'Back'],
    ['id'=>'F7', 'x'=>695, 'y'=>210, 'type'=>'four',  'zone'=>'Back'],
    ['id'=>'F8', 'x'=>475, 'y'=>360, 'type'=>'four',  'zone'=>'Back'],
    ['id'=>'F9', 'x'=>585, 'y'=>360, 'type'=>'four',  'zone'=>'Back'],
    ['id'=>'F10','x'=>695, 'y'=>360, 'type'=>'four',  'zone'=>'Back'],
  ];

  foreach($tables as $t):
    $taken = isTaken($t['id']);
    $isCouple = $t['type']==='couple';
    $tw = $isCouple?44:64; $th=$isCouple?24:34;
    $sw = $isCouple?18:16; $sh=$isCouple?14:13;
    $cols = $isCouple?2:4;
  ?>
  <div class="tbl <?=$taken?'taken':''?>"
       style="left:<?=$t['x']?>px;top:<?=$t['y']?>px"
       data-id="<?=$t['id']?>" data-zone="<?=$t['zone']?>"
       data-type="<?=$isCouple?'Couple (2 org)':'Meja 4 Orang'?>"
       onclick="selectTable(this)">
    <!-- top seats -->
    <div class="seats-row">
      <?php for($s=0;$s<$cols;$s++): ?>
      <div class="seat" style="width:<?=$sw?>px;height:<?=$sh?>px;border-radius:3px 3px 1px 1px"></div>
      <?php endfor; ?>
    </div>
    <div class="tbl-top" style="width:<?=$tw?>px;height:<?=$th?>px"><?=$t['id']?></div>
    <!-- bottom seats (four only) -->
    <?php if(!$isCouple): ?>
    <div class="seats-row">
      <?php for($s=0;$s<4;$s++): ?>
      <div class="seat" style="width:<?=$sw?>px;height:<?=$sh?>px;border-radius:1px 1px 3px 3px"></div>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <div class="tbl-lbl"><?=$t['id']?></div>
  </div>
  <?php endforeach; ?>

  <!-- BAR STOOLS (B1–B10 above counter) -->
  <?php
  for($b=1;$b<=10;$b++):
    $bx = 210 + ($b-1)*17;
    $by = 308;
    $btaken = isTaken('B'.$b);
  ?>
  <div class="stool-unit free-seat"
       style="left:<?=$bx?>px;top:<?=$by?>px"
       data-id="B<?=$b?>" data-zone="Bar" data-type="Free Seat (Walk-in Only)">
    <div class="stool-circle"></div>
    <div class="stool-lbl">B<?=$b?></div>
  </div>
  <?php endfor; ?>


</div><!-- /floorplan -->
</div><!-- /fp-wrap -->

<!-- CONFIRM BAR -->
<div class="cbar" id="cbar">
  <div class="info">
    <div class="info-chip">Meja: <span id="s-id">—</span></div>
    <div class="info-chip">Zona: <span id="s-zone">—</span></div>
    <div class="info-chip">Tipe: <span id="s-type">—</span></div>
  </div>
  <button class="btn-ok" onclick="goToStep2()">Lanjut → Pilih Menu</button>
</div>
</div><!-- /step1-content -->

<!-- ════════════ STEP 2: MENU PRE-ORDER ════════════ -->
<div id="step2">
  <div class="menu-order-wrap">
    <h2>Pilihan Menu</h2>
    <p class="sub">Tambahkan pesanan Anda sebelum tiba — opsional. Pesanan akan disiapkan saat Anda datang.</p>
    <div class="menu-cats">
      <button class="cat-btn active" onclick="filterCat('all',this)">Semua</button>
      <button class="cat-btn" onclick="filterCat('coffee',this)">☕ Signature Coffee</button>
      <button class="cat-btn" onclick="filterCat('brew',this)">🫖 Manual Brew</button>
      <button class="cat-btn" onclick="filterCat('noncoffee',this)">🧃 Non-Coffee</button>
      <button class="cat-btn" onclick="filterCat('pastry',this)">🥐 Pastries</button>
      <button class="cat-btn" onclick="filterCat('bites',this)">🍽️ Bites</button>
    </div>
    <div class="menu-grid" id="menuGrid"></div>
  </div>
  <!-- Order bar -->
  <div class="order-bar">
    <div>
      <div class="total-info">Total Pesanan (<span id="item-count">0</span> item)</div>
      <div class="total-price">Rp <span id="total-price">0</span></div>
    </div>
    <div style="display:flex;align-items:center;gap:.5rem">
      <span class="skip-link" onclick="goToStep3()">Lanjut tanpa pesan</span>
      <button class="btn-ok" onclick="goToStep3()">Lanjut → Konfirmasi</button>
    </div>
  </div>
</div>

<!-- ════════════ STEP 3: KONFIRMASI & PEMBAYARAN ════════════ -->
<div id="step3">
  <div class="confirm-wrap">
    <div class="confirm-card">
      <div style="font-size:2rem;margin-bottom:.5rem">🎉</div>
      <h2>Ringkasan Reservasi</h2>
      <p class="sub" style="color:rgba(255,255,255,.4);font-size:.78rem;margin-top:.2rem">Periksa semua detail sebelum mengkonfirmasi</p>

      <!-- Booking Info -->
      <div class="confirm-section">
        <h6>Informasi Reservasi</h6>
        <div class="info-row"><span>Nama</span><strong><?=$nama?:'—'?></strong></div>
        <div class="info-row"><span>Tanggal</span><strong><?=$tanggal?:'—'?></strong></div>
        <div class="info-row"><span>Pukul</span><strong><?=$waktu?:'—'?></strong></div>
        <div class="info-row"><span>Meja</span><strong id="c3-table">—</strong></div>
        <div class="info-row"><span>Zona</span><strong id="c3-zone">—</strong></div>
        <div class="info-row"><span>Tipe</span><strong id="c3-type">—</strong></div>
      </div>

      <!-- Order List -->
      <div class="confirm-section" id="c3-order-section">
        <h6>Pre-Order Menu</h6>
        <div id="c3-order-list"><p style="color:rgba(255,255,255,.25);font-size:.8rem;font-style:italic">Tidak ada pesanan</p></div>
        <div class="total-line"><span>Total</span><span>Rp <span id="c3-total">0</span></span></div>
      </div>

      <!-- Pay at Venue Notice -->
      <div class="pay-notice">
        <div class="icon">💵</div>
        <div>
          <div class="ptitle">Bayar di Tempat</div>
          <div class="pdesc">Tidak diperlukan pembayaran di muka. Silakan datang dan tunjukkan kode reservasi Anda ke kasir. Pembayaran dilakukan langsung di Senja Coffee.</div>
        </div>
      </div>

      <button class="btn-reserve" onclick="finishBooking()">✓ Konfirmasi Reservasi</button>
      <button class="btn-back" onclick="goToStep2()">← Kembali ke Menu</button>
    </div>
  </div>
</div>

<script src="../booking.js"></script>
<script>
// ── State ──────────────────────────────
let sel = null;
const order = {}; // {id: {name, price, qty}}

// ── Menu Data ──────────────────────────
const menuItems = <?php echo json_encode($menuItems, JSON_UNESCAPED_UNICODE); ?>;

// ── Step 1: Seat Selection ─────────────
function selectTable(el){
  if(el.classList.contains('taken'))return;
  clearAll();
  if(sel===el){sel=null;updateBar();return;}
  el.classList.add('selected');
  sel=el; updateBar();
}
function selectStool(el){
  if(el.classList.contains('taken'))return;
  clearAll();
  if(sel===el){sel=null;updateBar();return;}
  el.classList.add('stool-sel');
  sel=el; updateBar();
}
function clearAll(){
  document.querySelectorAll('.tbl.selected').forEach(e=>e.classList.remove('selected'));
  document.querySelectorAll('.stool-unit.stool-sel').forEach(e=>e.classList.remove('stool-sel'));
}
function updateBar(){
  const cb=document.getElementById('cbar');
  if(!sel){cb.classList.remove('show');return;}
  document.getElementById('s-id').textContent=sel.dataset.id;
  document.getElementById('s-zone').textContent=sel.dataset.zone;
  document.getElementById('s-type').textContent=sel.dataset.type;
  cb.classList.add('show');
}

// ── Step Navigation ────────────────────
function setStep(n){
  document.getElementById('step1-content').style.display = n===1?'':'none';
  document.getElementById('cbar').style.display          = n===1?'':'none';
  document.getElementById('step2').style.display         = n===2?'block':'none';
  document.getElementById('step3').style.display         = n===3?'block':'none';
  // update step indicator
  ['s1','s2','s3'].forEach((id,i)=>{
    const el=document.getElementById(id);
    el.className='step'+(i+1<n?' done':i+1===n?' active':'');
  });
  ['sl1','sl2'].forEach((id,i)=>{
    document.getElementById(id).className='step-line'+(i+1<n?' done':'');
  });
  document.querySelector('.brand-sub').textContent=
    n===1?'Pilih Meja':n===2?'Pilih Menu':'Konfirmasi';
  window.scrollTo({top:0,behavior:'smooth'});
}

function goToStep2(){
  if(!sel)return;
  renderMenu('all');
  setStep(2);
}
function goToStep3(){
  // populate step3
  document.getElementById('c3-table').textContent=sel.dataset.id;
  document.getElementById('c3-zone').textContent=sel.dataset.zone;
  document.getElementById('c3-type').textContent=sel.dataset.type;
  renderOrderSummary();
  setStep(3);
}

// ── Step 2: Menu Logic ─────────────────
let activeCat='all';
function renderMenu(cat){
  activeCat=cat;
  const grid=document.getElementById('menuGrid');
  const items=cat==='all'?menuItems:menuItems.filter(m=>m.cat===cat);
  grid.innerHTML=items.map(m=>`
    <div class="menu-card">
      <div class="m-content">
        ${m.img ? `<img src="${m.img}" class="m-img" onerror="this.style.display='none'">` : ''}
        <div>
          <div class="mname">${m.name}</div>
          <div class="mdesc">${m.desc}</div>
          <div class="mprice">Rp ${m.price.toLocaleString('id-ID')}</div>
        </div>
      </div>
      <div class="qty-ctrl">
        <button class="qty-btn" onclick="changeQty('${m.id}',-1)">−</button>
        <span class="qty-num" id="qty-${m.id}">${order[m.id]?.qty||0}</span>
        <button class="qty-btn" onclick="changeQty('${m.id}',1,'${m.name}',${m.price})">+</button>
      </div>
    </div>
  `).join('');
}
function filterCat(cat,btn){
  document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  renderMenu(cat);
}
function changeQty(id,delta,name,price){
  if(!order[id]) order[id]={name,price,qty:0};
  order[id].qty=Math.max(0,order[id].qty+delta);
  if(order[id].qty===0) delete order[id];
  const el=document.getElementById('qty-'+id);
  if(el) el.textContent=order[id]?.qty||0;
  updateOrderBar();
}
function updateOrderBar(){
  const count=Object.values(order).reduce((s,v)=>s+v.qty,0);
  const total=Object.values(order).reduce((s,v)=>s+v.qty*v.price,0);
  document.getElementById('item-count').textContent=count;
  document.getElementById('total-price').textContent=total.toLocaleString('id-ID');
}

// ── Step 3: Summary Logic ──────────────
function renderOrderSummary(){
  const list=document.getElementById('c3-order-list');
  const total=Object.values(order).reduce((s,v)=>s+v.qty*v.price,0);
  if(!Object.keys(order).length){
    list.innerHTML='<p style="color:rgba(255,255,255,.25);font-size:.8rem;font-style:italic">Tidak ada pesanan — bayar saat tiba</p>';
  } else {
    list.innerHTML=Object.values(order).map(v=>`
      <div class="order-list-item">
        <span class="iname">${v.name}</span>
        <span class="iqty">x${v.qty}</span>
        <span class="iamt">Rp ${(v.qty*v.price).toLocaleString('id-ID')}</span>
      </div>
    `).join('');
  }
  document.getElementById('c3-total').textContent=total.toLocaleString('id-ID');
}
function finishBooking(){
  const code='SCR-'+Math.random().toString(36).substr(2,6).toUpperCase();
  document.getElementById('popup-code').textContent  = code;
  document.getElementById('popup-table').textContent = sel.dataset.id+' — '+sel.dataset.zone;
  document.getElementById('popup-type').textContent  = sel.dataset.type;
  const popup = document.getElementById('success-popup');
  popup.style.display = 'flex';
  const card = popup.querySelector('.popup-card');
  card.style.animation='none'; card.offsetHeight;
  card.style.animation='popIn .4s cubic-bezier(.34,1.56,.64,1)';
}

// ── Live Sync Logic ────────────────────
async function syncTables() {
  try {
    const res = await fetch('../api_tables.php');
    const data = await res.json();
    
    // Update all tables/stools in the UI
    document.querySelectorAll('.tbl, .stool-unit').forEach(el => {
      const id = el.dataset.id;
      const isAvail = data[id]; // 1 = available, 0 = taken
      
      if (isAvail === 0) {
        el.classList.add('taken');
        // If the table we just selected becomes taken by someone else
        if (sel === el) {
          sel = null;
          updateBar();
          alert('Maaf, meja ' + id + ' baru saja dipesan oleh orang lain.');
        }
      } else {
        el.classList.remove('taken');
      }
    });
  } catch (e) { console.error('Sync failed', e); }
}

// Sync every 5 seconds
setInterval(syncTables, 5000);
</script>

<!-- ══════ SUCCESS POPUP ══════ -->
<div id="success-popup" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);backdrop-filter:blur(10px);z-index:9999;align-items:center;justify-content:center;">
  <div class="popup-card" style="background:linear-gradient(145deg,#2a2018,#1e1810);border:1px solid rgba(197,160,89,.4);border-radius:22px;padding:2.5rem;max-width:430px;width:90%;text-align:center;box-shadow:0 30px 80px rgba(0,0,0,.7);font-family:'Poppins',sans-serif;color:#fff;animation:popIn .4s cubic-bezier(.34,1.56,.64,1);">

    <div style="font-size:3rem;margin-bottom:.5rem">☕</div>
    <h2 style="font-family:'Playfair Display',serif;color:#C5A059;font-size:1.6rem;margin-bottom:.2rem">Reservasi Berhasil!</h2>
    <p style="color:rgba(255,255,255,.4);font-size:.78rem;margin-bottom:1.5rem">Kami menantikan kehadiran Anda di Senja Coffee</p>

    <!-- Kode Reservasi -->
    <div style="background:rgba(197,160,89,.1);border:1.5px dashed rgba(197,160,89,.5);border-radius:14px;padding:1.2rem;margin-bottom:1.2rem;">
      <div style="font-size:.6rem;text-transform:uppercase;letter-spacing:2.5px;color:rgba(255,255,255,.3);margin-bottom:.4rem">Kode Reservasi</div>
      <div id="popup-code" style="font-size:2rem;font-weight:700;color:#C5A059;letter-spacing:5px;font-family:'Playfair Display',serif">—</div>
      <div style="font-size:.65rem;color:rgba(255,255,255,.2);margin-top:.4rem">Tunjukkan kode ini ke kasir saat tiba</div>
    </div>

    <!-- Detail -->
    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:.9rem;margin-bottom:1.2rem;text-align:left;font-size:.82rem;">
      <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.06)">
        <span style="color:rgba(255,255,255,.4)">Meja</span>
        <strong id="popup-table" style="color:#fff">—</strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:5px 0">
        <span style="color:rgba(255,255,255,.4)">Tipe</span>
        <strong id="popup-type" style="color:#fff">—</strong>
      </div>
    </div>

    <!-- Bayar di Tempat -->
    <div style="background:rgba(46,204,113,.07);border:1px solid rgba(46,204,113,.2);border-radius:10px;padding:.85rem 1rem;font-size:.75rem;color:rgba(255,255,255,.45);margin-bottom:1.5rem;text-align:left;display:flex;gap:.7rem;align-items:flex-start;">
      <span style="font-size:1.3rem;flex-shrink:0;margin-top:1px">💵</span>
      <span><strong style="color:#2ecc71;display:block;margin-bottom:3px">Bayar di Tempat</strong>Tidak diperlukan pembayaran di muka. Silakan datang dan tunjukkan kode reservasi ke kasir.</span>
    </div>

    <button onclick="window.location.href='../Senja-Kopi.php'" style="width:100%;background:linear-gradient(135deg,#C5A059,#d4a84b);color:#1a1612;border:none;padding:13px;border-radius:25px;font-size:.95rem;font-weight:700;cursor:pointer;font-family:'Poppins',sans-serif;letter-spacing:.5px;box-shadow:0 6px 20px rgba(197,160,89,.4);transition:all .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
      Kembali ke Beranda
    </button>
  </div>
</div>

<style>
  #success-popup.open{display:flex}
  @keyframes popIn{from{transform:scale(.7);opacity:0}to{transform:scale(1);opacity:1}}
</style>
</body>
</html>
