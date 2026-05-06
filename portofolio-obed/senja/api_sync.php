<?php
/**
 * api_sync.php — Bundled API (Poin 5: API Bundling)
 *
 * Returns ALL page data in a single HTTP request:
 *   - menus     (grouped by category)
 *   - beans     (list of coffee beans)
 *   - chart     (sold_count array for Chart.js)
 *   - contacts  (keyed by contact_type)
 *
 * The frontend calls this once every 30 seconds instead of hitting
 * 4 separate endpoints, reducing server connections by ~75%.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

// ── 1. MENUS ────────────────────────────────────────────────────────
$menus = [];
$r = mysqli_query($conn, "SELECT category, name, description, price, image_url FROM menus");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $menus[$row['category']][] = [
            'name'        => $row['name'],
            'description' => $row['description'],
            'price'       => $row['price'],
            'image_url'   => $row['image_url'] ?? ''
        ];
    }
}

// ── 2. COFFEE BEANS ─────────────────────────────────────────────────
$beans = [];
$r = mysqli_query($conn, "SELECT id, name, roast_level, origin, notes, image_url FROM coffee_beans ORDER BY name ASC");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $beans[] = [
            'id'          => $row['id'],
            'name'        => $row['name'],
            'roast_level' => $row['roast_level'],
            'origin'      => $row['origin'],
            'notes'       => $row['notes'],
            'image_url'   => $row['image_url']
        ];
    }
}

// ── 3. CHART DATA ────────────────────────────────────────────────────
$chart = [];
$r = mysqli_query($conn, "SELECT sold_count FROM favorite_coffee");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $chart[] = (int)$row['sold_count'];
    }
}

// ── 4. CONTACTS ──────────────────────────────────────────────────────
$contacts = [];
$r = mysqli_query($conn, "SELECT contact_type, role, person_name, link_url, display_value FROM contacts");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $contacts[$row['contact_type']] = [
            'role'          => $row['role'],
            'person_name'   => $row['person_name'],
            'link_url'      => $row['link_url'],
            'display_value' => $row['display_value']
        ];
    }
}

// ── BUNDLE & SEND ─────────────────────────────────────────────────────
echo json_encode([
    'menus'    => $menus,
    'beans'    => $beans,
    'chart'    => $chart,
    'contacts' => $contacts
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
