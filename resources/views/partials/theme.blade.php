{{--
    SOPOD DESIGN SYSTEM v2 — corporate finance/ERP theme
    ==================================================================
    Included once from layouts/app.blade.php, immediately after the
    Tailwind CDN <script>. Three mechanisms:

      1. tailwind.config — remaps the dark palette the 241 existing
         views already use, so they flip without being edited.
      2. The <style> block — the component system, plus the fixes a
         palette remap cannot express.
      3. The <svg> sprite — monochrome outline icons (Lucide) for the
         sidebar, replacing emoji. Inline, so there is no CDN request.

    Rule of thumb throughout: 95% of the screen is white/gray/navy.
    Blue means actionable, selected, or important — never decoration.

    To revert: remove the @include from layouts/app.blade.php.
--}}

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                },
                colors: {
                    gray: {
                        50:  '#F9FAFB',
                        100: '#F3F4F6',
                        200: '#E5E7EB',
                        300: '#6B7280',  /* text-gray-300 was secondary text on dark */
                        400: '#6B7280',
                        500: '#6B7280',
                        600: '#374151',
                        700: '#E5E7EB',  /* borders, and legacy "card" surfaces */
                        800: '#FFFFFF',  /* card surface */
                        900: '#F6F8FB',  /* page background / inset panel */
                    },
                    purple: {
                        50:'#EFF6FF',100:'#DBEAFE',200:'#BFDBFE',300:'#93C5FD',400:'#60A5FA',
                        500:'#3B82F6',600:'#2563EB',700:'#1D4ED8',800:'#1E40AF',900:'#1E3A8A',
                    },
                    ink:     '#111827',
                    body:    '#374151',
                    muted:   '#6B7280',
                    line:    '#E5E7EB',
                    canvas:  '#F6F8FB',
                    surface: '#FFFFFF',
                    navy:    '#172033',
                },
                borderRadius: { DEFAULT: '6px', md: '6px', lg: '8px', xl: '8px', '2xl': '8px' },
                boxShadow: {
                    DEFAULT: '0 1px 2px rgba(16,24,40,.04)',
                    sm: '0 1px 2px rgba(16,24,40,.04)',
                    md: '0 1px 2px rgba(16,24,40,.04)',
                    lg: '0 1px 2px rgba(16,24,40,.04)',
                    xl: '0 1px 3px rgba(16,24,40,.06)',
                    '2xl': '0 1px 3px rgba(16,24,40,.06)',
                },
            },
        },
    };
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        /* ---- Surfaces ---- */
        --canvas:        #F6F8FB;
        --surface:       #FFFFFF;
        --sidebar:       #172033;
        --sidebar-hover: #202C43;
        --sidebar-active:#29457A;

        /* ---- Accent ---- */
        --primary:       #2563EB;
        --primary-hover: #1D4ED8;

        /* ---- Text ---- */
        --heading: #111827;
        --body:    #374151;
        --muted:   #6B7280;
        --line:    #E5E7EB;

        /* ---- Semantic ---- */
        --success: #15803D;
        --warning: #B45309;
        --danger:  #B91C1C;
        --info:    #2563EB;

        /* ---- Spacing scale: 4 / 8 / 12 / 16 / 24 / 32 ---- */
        --sp-1:4px; --sp-2:8px; --sp-3:12px; --sp-4:16px; --sp-6:24px; --sp-8:32px;

        --radius: 6px;
        --radius-lg: 8px;
        --shadow: 0 1px 2px rgba(16,24,40,.04);
        --shadow-md: 0 1px 3px rgba(16,24,40,.06);
    }

    * { box-sizing: border-box; }

    /* ==========================================================
       1. BASE — compact, not spacious. This is software people
          keep open for eight hours.
       ========================================================== */
    body {
        background-color: var(--canvas) !important;
        color: var(--body);
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.45;
        -webkit-font-smoothing: antialiased;
    }

    h1, h2, h3, h4, h5 { color: var(--heading); font-weight: 600; }

    /* Type scale — clamp the oversized headings the views hardcode */
    h1, .text-3xl, .text-4xl { font-size: 24px !important; line-height: 1.25; letter-spacing: -.01em; }
    h2, .text-2xl { font-size: 20px !important; line-height: 1.3; }
    h3, .text-xl  { font-size: 16px !important; line-height: 1.35; }
    .text-lg { font-size: 15px !important; }
    .text-sm { font-size: 13px !important; }
    .text-xs { font-size: 12px !important; }

    /* ==========================================================
       2. TEXT COLOR CORRECTION
       text-white must read dark on a now-white card, but stay white
       on a filled button or the navy sidebar. bg-gray-* is absent
       from the whitelist — those are the surfaces that became light.
       ========================================================== */
    /* !important is required here: the Tailwind Play CDN injects its own
       stylesheet into <head> AFTER this block, so an unweighted rule loses
       to its .text-white{color:#fff} and titles render white-on-white. */
    .text-white, .text-gray-50, .text-gray-100, .text-gray-200 { color: var(--body) !important; }
    /* A heading rendered as a link still reads as a heading */
    a.text-white, a.text-gray-50, a.text-gray-100 { color: var(--heading) !important; }
    a.text-white:hover, a.text-gray-50:hover, a.text-gray-100:hover { color: var(--primary) !important; }
    /* Secondary "View all" style links must be legible, not near-white */
    a.text-gray-200, a.text-gray-300, a.text-gray-400, a.text-gray-500 { color: var(--primary) !important; }

    #sidebar .text-white, #sidebar .text-gray-100, #sidebar .text-gray-200,
    #sidebar .text-gray-300, #sidebar .text-gray-400,
    button[class*="bg-blue-"], a[class*="bg-blue-"],
    button[class*="bg-green-"], a[class*="bg-green-"],
    button[class*="bg-emerald-"], a[class*="bg-emerald-"],
    button[class*="bg-red-"], a[class*="bg-red-"],
    button[class*="bg-rose-"], a[class*="bg-rose-"],
    button[class*="bg-amber-"], a[class*="bg-amber-"],
    button[class*="bg-yellow-"], a[class*="bg-yellow-"],
    button[class*="bg-orange-"], a[class*="bg-orange-"],
    button[class*="bg-purple-"], a[class*="bg-purple-"],
    button[class*="bg-indigo-"], a[class*="bg-indigo-"],
    button[class*="bg-gradient"], a[class*="bg-gradient"],
    span[class*="bg-blue-5"], span[class*="bg-blue-6"],
    span[class*="bg-green-5"], span[class*="bg-green-6"],
    span[class*="bg-red-5"], span[class*="bg-red-6"],
    span[class*="bg-gray-6"],
    .btn-primary, .btn-danger, .btn-success {
        color: #FFFFFF !important;
    }

    h1.text-white, h2.text-white, h3.text-white, h4.text-white,
    p.text-white, td.text-white, th.text-white, label.text-white {
        color: var(--heading) !important;
    }
    p.text-white, td.text-white { color: var(--body) !important; }

    .text-gray-300, .text-gray-400, .text-gray-500 { color: var(--muted) !important; }

    /* ==========================================================
       3. SIDEBAR — pinned dark; presentation only, structure kept
       ========================================================== */
    #sidebar.sidebar {
        background-color: var(--sidebar) !important;
        border-right: none !important;
        color: #CBD5E1 !important;
        padding: 0 8px 24px;
    }
    #sidebar .sidebar-header { padding: 18px 12px 10px !important; justify-content: flex-start !important; }
    #sidebar .sidebar-header h2 {
        color: #FFFFFF !important;
        font-size: 14px !important;
        font-weight: 600;
        letter-spacing: .09em;
        text-transform: uppercase;
    }
    #sidebar nav { margin-top: 4px !important; }
    #sidebar nav > * + * { margin-top: 1px !important; }

    #sidebar nav a,
    #sidebar nav button {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 7px 10px !important;
        border-radius: 6px;
        color: #C3CBD9 !important;
        font-size: 13px !important;
        font-weight: 400;
        line-height: 1.35;
        text-align: left;
        transition: background-color .12s ease, color .12s ease;
    }
    #sidebar nav a:hover, #sidebar nav button:hover {
        background-color: var(--sidebar-hover) !important;
        color: #FFFFFF !important;
    }
    #sidebar nav a.active,
    #sidebar nav .submenu a.active {
        background-color: var(--sidebar-active) !important;
        color: #FFFFFF !important;
        font-weight: 500;
        border-left: none !important;
    }
    #sidebar nav button.parent-active { background-color: var(--sidebar-hover) !important; color:#fff !important; }

    /* Icons: uniform box, muted until hovered/active */
    #sidebar .nav-icon {
        width: 17px; height: 17px;
        flex: none;
        stroke: currentColor;
        opacity: .72;
    }
    #sidebar a:hover .nav-icon, #sidebar button:hover .nav-icon,
    #sidebar a.active .nav-icon { opacity: 1; }

    /* Chevrons: one consistent affordance, rotating in place */
    #sidebar .chevron {
        width: 13px; height: 13px;
        flex: none;
        margin-left: auto;
        opacity: .5;
        transition: transform .18s ease;
    }
    #sidebar button.active .chevron { transform: rotate(180deg); }

    /* Submenus: indented, quieter, aligned under the label */
    #sidebar .submenu { margin-left: 27px !important; padding-left: 10px; border-left: 1px solid rgba(255,255,255,.09); }
    #sidebar .submenu a {
        padding: 6px 8px !important;
        font-size: 12.5px !important;
        color: #9AA6B8 !important;
    }
    #sidebar .submenu a:hover { color: #FFFFFF !important; background-color: var(--sidebar-hover) !important; }

    /* ==========================================================
       4. TOP BAR
       ========================================================== */
    #topbar.bg-gray-800 {
        background-color: var(--surface) !important;
        border: none !important;
        border-bottom: 1px solid var(--line) !important;
        box-shadow: none !important;
        padding: 0 24px !important;
        height: 56px;
    }
    #topbar h1 { font-size: 15px !important; font-weight: 600; color: var(--heading) !important; }
    .menu-btn {
        display:inline-flex; align-items:center; justify-content:center;
        width: 30px; height: 30px; border-radius: var(--radius);
        background: transparent; border: 1px solid transparent; color: var(--muted); cursor: pointer;
    }
    .menu-btn:hover { background:#F3F4F6; color: var(--heading); }
    .menu-icon { width: 17px; height: 17px; stroke: currentColor; }

    /* Inline indicator icon — sized to sit on the text baseline */
    .ico {
        width: 14px; height: 14px; flex: none;
        stroke: currentColor; fill: none;
        vertical-align: -2px; display: inline-block;
    }
    .ico-sm { width: 12px; height: 12px; vertical-align: -1px; }
    #topbar, #topbar a, #topbar span, #topbar button { color: var(--body) !important; font-size: 13px; }
    #topbar a:hover, #topbar button:hover { color: var(--primary) !important; }
    #topbar .bg-gray-800 { border: 1px solid var(--line) !important; box-shadow: var(--shadow-md) !important; }

    /* ==========================================================
       5. CONTENT WIDTH STANDARDS
       Dashboards and tables use the wide container; forms and
       imports use the narrow one.
       ========================================================== */
    .page-wide   { max-width: 1600px; margin-inline: auto; width: 100%; }
    .page-narrow { max-width: 880px;  margin-inline: auto; width: 100%; }
    .container.mx-auto { max-width: 1600px !important; }

    /* The shell already supplies page padding (p-4 / md:p-8). Views that add
       their own full-height wrapper or a second layer of padding made pages
       start at different positions and stretch to a full empty screen.
       Normalise only the page's OUTERMOST wrapper — cards are untouched. */
    #pagebody { min-width: 0; }
    #pagebody > .container,
    #pagebody > div.min-h-screen,
    #pagebody > div[class*="max-w-"],
    #pagebody > form.min-h-screen {
        max-width: 1600px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        width: 100%;
        min-height: 0 !important;
        background-color: transparent !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #pagebody > div.min-h-screen,
    #pagebody > form.min-h-screen {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    /* A card sitting directly in the page keeps its own padding */
    #pagebody > .bg-gray-800 { padding: var(--sp-6) !important; max-width: 1600px; margin-inline: auto; }

    /* Uniform vertical rhythm between a page's top-level blocks */
    #pagebody > * > * + * { margin-top: var(--sp-4); }

    /* ==========================================================
       6. SURFACES — flatten the nested-box look
       ========================================================== */
    .bg-gray-800 {
        background-color: var(--surface) !important;
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
    }
    /* bg-gray-700 was a dark card surface in the old theme; as a
       container it becomes a white card, not a gray slab. */
    div.bg-gray-700, section.bg-gray-700 {
        background-color: var(--surface) !important;
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
    }
    /* Inset panel inside a card: spacing separates it, not a heavy box */
    .bg-gray-800 .bg-gray-900, .bg-gray-800 .bg-gray-800 {
        background-color: var(--canvas) !important;
        box-shadow: none !important;
    }
    .bg-gray-800 .bg-gray-800 { border-color: var(--line) !important; }

    /* ==========================================================
       7. GRADIENTS
       v1 turned every gradient solid blue, which painted whole
       filter panels as one saturated rectangle. Containers become
       quiet white panels; only buttons and links keep a fill, and
       they keep their own semantic hue.
       ========================================================== */
    div[class*="bg-gradient"], section[class*="bg-gradient"],
    form[class*="bg-gradient"], header[class*="bg-gradient"],
    aside[class*="bg-gradient"], li[class*="bg-gradient"] {
        background-image: none !important;
        background-color: var(--surface) !important;
        border: 1px solid var(--line) !important;
        border-radius: var(--radius-lg) !important;
        box-shadow: var(--shadow) !important;
        color: var(--body) !important;
    }
    button[class*="bg-gradient"], a[class*="bg-gradient"], input[type="submit"][class*="bg-gradient"] {
        background-image: none !important;
        background-color: var(--primary) !important;
        border: 1px solid var(--primary) !important;
        box-shadow: none !important;
    }
    button[class*="from-green"], a[class*="from-green"],
    button[class*="from-emerald"], a[class*="from-emerald"] {
        background-color: var(--success) !important; border-color: var(--success) !important;
    }
    button[class*="from-red"], a[class*="from-red"],
    button[class*="from-rose"], a[class*="from-rose"] {
        background-color: var(--danger) !important; border-color: var(--danger) !important;
    }
    button[class*="from-amber"], a[class*="from-amber"],
    button[class*="from-yellow"], a[class*="from-yellow"] {
        background-color: var(--warning) !important; border-color: var(--warning) !important;
    }

    /* Saturated tint blocks used as panels -> quiet neutral panels */
    div[class*="bg-blue-50"], div[class*="bg-blue-100"],
    div[class*="bg-indigo-50"], div[class*="bg-sky-50"] {
        background-color: #F8FAFC !important;
        border-color: var(--line) !important;
        color: var(--body) !important;
    }

    /* ==========================================================
       8. FILTER PANEL — same everywhere
       ========================================================== */
    .filter-panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        padding: var(--sp-4) var(--sp-6);
        margin-bottom: var(--sp-4);
    }
    .filter-title {
        font-size: 12px; font-weight: 600; letter-spacing: .06em;
        text-transform: uppercase; color: var(--muted);
        margin: 0 0 var(--sp-3);
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--sp-3) var(--sp-4);
        align-items: end;
    }
    .filter-actions {
        display: flex; align-items: center; gap: var(--sp-3);
        margin-top: var(--sp-4); padding-top: var(--sp-3);
        border-top: 1px solid var(--line);
    }

    /* ==========================================================
       9. TABLES — dense, horizontal dividers only
       ========================================================== */
    table { border-collapse: collapse; width: 100%; font-size: 13px; background: transparent !important; border: none !important; }

    table thead tr, table thead tr.bg-gray-700, table thead tr.bg-gray-800 {
        background-color: #F9FAFB !important;
        border-bottom: 1px solid var(--line);
        box-shadow: none !important;
    }
    table thead th {
        color: var(--muted) !important;
        font-size: 12px !important;
        font-weight: 600;
        letter-spacing: .03em;
        text-transform: uppercase;
        padding: 8px 12px !important;
        border: none !important;
        white-space: nowrap;
        text-align: left;
    }
    table tbody tr { border-bottom: 1px solid var(--line); background: var(--surface); }
    table tbody tr:last-child { border-bottom: none; }
    table tbody tr:hover { background-color: var(--body); }
    table tbody td {
        padding: 8px 12px !important;
        border: none !important;
        color: var(--body);
        font-size: 13px !important;
        vertical-align: middle;
    }
    table td.text-right, table th.text-right, table td.num, table th.num {
        text-align: right; font-variant-numeric: tabular-nums;
    }
    .table-sticky thead th {
        position: sticky; top: 0; z-index: 10;
        background-color: #F9FAFB !important;
        box-shadow: inset 0 -1px 0 var(--line);
    }
    .table-wrap { overflow-x: auto; }

    /* Empty state — same everywhere */
    .empty-state { padding: 48px 24px; text-align: center; color: var(--muted); font-size: 13px; }
    .empty-state-title { font-size: 14px; font-weight: 500; color: var(--heading); margin-bottom: var(--sp-1); }
    .empty-note { font-size: 13px; color: var(--muted); }
    tr.sopod-empty-row td {
        text-align: center !important;
        padding: 40px 16px !important;
        color: var(--muted) !important;
        background: var(--surface);
    }

    /* ==========================================================
       10. BUTTONS — one hierarchy
       ========================================================== */
    .btn-primary, .btn-secondary, .btn-danger, .btn-success, .btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 7px 13px;
        border-radius: var(--radius);
        font-size: 13px; font-weight: 500; line-height: 1.3;
        cursor: pointer; white-space: nowrap; text-decoration: none;
        transition: background-color .12s ease, border-color .12s ease;
    }
    .btn-primary   { background: var(--primary); color:#fff; border:1px solid var(--primary); }
    .btn-primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); }
    .btn-secondary { background:#fff; color: var(--body); border:1px solid var(--line); }
    .btn-secondary:hover { background:#F9FAFB; border-color:#D1D5DB; }
    .btn-danger    { background: var(--danger); color:#fff; border:1px solid var(--danger); }
    .btn-success   { background: var(--success); color:#fff; border:1px solid var(--success); }
    .btn-ghost     { background:transparent; color: var(--muted); border:1px solid transparent; }
    .btn-ghost:hover { background:#F3F4F6; color: var(--heading); }

    /* Existing utility buttons inherit the compact shape */
    button, a.inline-flex, .btn, input[type="submit"] { border-radius: var(--radius); }
    button[class*="px-6"], a[class*="px-6"] { padding-left: 13px !important; padding-right: 13px !important; }
    button[class*="py-2.5"], a[class*="py-2.5"] { padding-top: 7px !important; padding-bottom: 7px !important; }

    :focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

    /* ==========================================================
       11. FORMS
       ========================================================== */
    input[type="text"], input[type="number"], input[type="date"], input[type="email"],
    input[type="password"], input[type="search"], input[type="tel"], input[type="file"],
    select, textarea {
        background-color: #FFFFFF !important;
        border: 1px solid #D1D5DB !important;
        color: var(--body) !important;
        border-radius: var(--radius) !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        line-height: 1.4;
        transition: border-color .12s ease, box-shadow .12s ease;
    }
    input::placeholder, textarea::placeholder { color:#9CA3AF !important; }
    input:focus, select:focus, textarea:focus {
        outline: none !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(37,99,235,.11) !important;
    }
    input:disabled, select:disabled, textarea:disabled {
        background-color:#F3F4F6 !important; color:#9CA3AF !important; cursor:not-allowed;
    }
    label { font-size: 13px; font-weight: 500; color: var(--heading); display:inline-block; margin-bottom: 5px; }
    .field-hint { font-size: 12px; color: var(--muted); margin-top: var(--sp-1); }
    .required::after { content:' *'; color: var(--danger); }
    .form-section-title {
        font-size: 12px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
        color: var(--muted); padding-bottom: var(--sp-2); margin-bottom: var(--sp-4);
        border-bottom: 1px solid var(--line);
    }

    /* ==========================================================
       12. STATUS BADGES — one vocabulary, system-wide
       ========================================================== */
    .badge {
        display:inline-flex; align-items:center; gap:5px;
        padding: 2px 8px; border-radius: 999px;
        font-size: 12px; font-weight: 500; line-height: 1.6; white-space: nowrap;
    }
    .badge::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; flex:none; }
    .badge-draft     { background:#F3F4F6; color:#4B5563; }
    .badge-pending   { background:#FEF3C7; color:#B45309; }
    .badge-approved  { background:#DBEAFE; color:#1D4ED8; }
    .badge-paid      { background:#DCFCE7; color:#15803D; }
    .badge-rejected  { background:#FEE2E2; color:#B91C1C; }
    .badge-overdue   { background:#FEE2E2; color:#B91C1C; }
    .badge-cancelled { background:#F3F4F6; color:#6B7280; }
    .badge-info      { background:#DBEAFE; color:#1D4ED8; }

    /* Legacy inline status pills -> same compact shape */
    span[class*="rounded"][class*="text-xs"][class*="px-2"] { border-radius: 999px; font-weight: 500; }

    /* ==========================================================
       13. PAGE HEADER — same everywhere
       ========================================================== */
    .page-header {
        display:flex; align-items:flex-start; justify-content:space-between; gap: var(--sp-4);
        padding-bottom: var(--sp-3); margin-bottom: var(--sp-4);
        border-bottom: 1px solid var(--line);
    }
    .page-title    { font-size: 24px !important; font-weight: 600; color: var(--heading); line-height: 1.25; margin:0; }
    .page-subtitle { font-size: 13px; color: var(--muted); margin-top: 2px; }
    .page-actions  { display:flex; align-items:center; gap: var(--sp-2); flex:none; }

    /* ==========================================================
       14. STAT TILES — small label, big value, tiny context
       ========================================================== */
    .stat-card {
        background: var(--surface); border:1px solid var(--line);
        border-radius: var(--radius-lg); padding: 16px 18px;
    }
    .stat-label { font-size: 12px; font-weight: 500; color: var(--muted); text-transform: none; }
    .stat-value {
        font-size: 26px; font-weight: 600; color: var(--heading);
        margin-top: 4px; font-variant-numeric: tabular-nums; line-height: 1.15;
    }
    .stat-delta { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .stat-delta.up { color: var(--success); } .stat-delta.down { color: var(--danger); }

    /* ==========================================================
       14b. SEMANTIC TINTS
       KPI and status blocks were built for a dark theme using deep
       800/900 tints (bg-red-900/40, bg-yellow-900/40 …). On a light
       workspace those read as saturated slabs. Map them to the light
       tint of the same hue so the meaning survives but the surface
       stays quiet. Buttons keep their fills.
       ========================================================== */
    div[class*="bg-red-9"], div[class*="bg-red-8"], span[class*="bg-red-9"],
    div[class*="bg-rose-9"] {
        background-color: #FEF2F2 !important; border-color: #FECACA !important;
    }
    div[class*="bg-yellow-9"], div[class*="bg-yellow-8"],
    div[class*="bg-amber-9"], div[class*="bg-amber-8"], span[class*="bg-amber-9"] {
        background-color: #FFFBEB !important; border-color: #FDE68A !important;
    }
    div[class*="bg-green-9"], div[class*="bg-green-8"],
    div[class*="bg-emerald-9"], div[class*="bg-emerald-8"], span[class*="bg-green-9"] {
        background-color: #F0FDF4 !important; border-color: #BBF7D0 !important;
    }
    div[class*="bg-blue-9"], div[class*="bg-blue-8"],
    div[class*="bg-indigo-9"], div[class*="bg-indigo-8"], span[class*="bg-blue-9"] {
        background-color: #EFF6FF !important; border-color: #BFDBFE !important;
    }
    div[class*="bg-purple-9"], div[class*="bg-violet-9"], div[class*="bg-purple-8"] {
        background-color: #F5F3FF !important; border-color: #DDD6FE !important;
    }
    div[class*="bg-gray-9"][class*="/"], div[class*="bg-slate-9"] {
        background-color: #F9FAFB !important;
    }

    /* The values inside them were light-on-dark; darken for contrast.
       Buttons are excluded — their text stays white. */
    p[class*="text-red-4"], p[class*="text-red-3"], span[class*="text-red-4"], .text-red-400 { color: var(--danger) !important; }
    p[class*="text-yellow-4"], p[class*="text-amber-4"], span[class*="text-yellow-4"], .text-yellow-400 { color: var(--warning) !important; }
    p[class*="text-green-4"], p[class*="text-emerald-4"], span[class*="text-green-4"], .text-green-400 { color: var(--success) !important; }
    p[class*="text-blue-4"], p[class*="text-indigo-4"], span[class*="text-blue-4"], .text-blue-400 { color: var(--primary) !important; }
    p[class*="text-purple-4"], span[class*="text-purple-4"] { color: #6D28D9 !important; }

    /* A left accent rail stays, but thinner and quieter than border-4 */
    div[class*="border-l-4"] { border-left-width: 3px !important; }

    /* KPI rows keep equal height regardless of how much text each carries.
       Height only — the card's own flex direction is left alone, or the
       analytics metric cards flip their icon below the label. */
    .grid > .stat-card, .grid > div.bg-gray-700, .grid > div.bg-gray-800,
    .metrics-grid > .metric-card, .grid > .kpi-card {
        height: 100%;
    }
    .grid, .metrics-grid { align-items: stretch; }

    /* Legacy dashboard tiles: bg-gray-700 + h3 + .text-3xl value.
       Retune to the same label/value rhythm without editing views. */
    div.bg-gray-700 > h3, div.bg-gray-800 > h3 {
        font-size: 12px !important; font-weight: 500 !important;
        color: var(--muted) !important; letter-spacing: 0;
    }
    div.bg-gray-700 > p[class*="text-3xl"], div.bg-gray-800 > p[class*="text-3xl"],
    div.bg-gray-700 > p[class*="text-4xl"], div.bg-gray-800 > p[class*="text-4xl"] {
        font-size: 26px !important; font-weight: 600 !important;
        color: var(--heading) !important; margin-top: 4px !important;
        font-variant-numeric: tabular-nums;
    }
    div.bg-gray-700[class*="p-6"], div.bg-gray-800[class*="p-6"] { padding: 16px 18px !important; }

    /* ==========================================================
       15. CARD + MODAL
       ========================================================== */
    .card { background: var(--surface); border:1px solid var(--line); border-radius: var(--radius-lg); box-shadow: var(--shadow); }
    .card-header {
        display:flex; align-items:center; justify-content:space-between;
        padding: 12px var(--sp-6); border-bottom: 1px solid var(--line);
    }
    .card-title { font-size: 16px; font-weight: 600; color: var(--heading); }
    .card-body  { padding: var(--sp-4) var(--sp-6); }
    .card-table { padding: 0; }

    .modal-panel {
        background: var(--surface); border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px rgba(16,24,40,.12); border:1px solid var(--line);
    }
    .modal-header {
        display:flex; align-items:center; justify-content:space-between;
        padding: 14px var(--sp-6); border-bottom:1px solid var(--line);
        font-size: 16px; font-weight: 600; color: var(--heading);
    }
    .modal-body   { padding: var(--sp-6); }
    .modal-footer {
        display:flex; justify-content:flex-end; gap: var(--sp-2);
        padding: 12px var(--sp-6); border-top:1px solid var(--line); background:#F9FAFB;
    }

    /* Alerts — flat, not saturated blocks */
    div[class*="bg-green-6"]:not(button):not(a) { background-color:#F0FDF4 !important; color:#15803D !important; border:1px solid #BBF7D0 !important; }
    div[class*="bg-red-6"]:not(button):not(a)   { background-color:#FEF2F2 !important; color:#B91C1C !important; border:1px solid #FECACA !important; }

    .border-gray-700, .border-gray-600, .border-gray-500 { border-color: var(--line) !important; }

    /* Rounded-2xl / shadow-2xl on panels reads as consumer SaaS; calm it down */
    .rounded-2xl, .rounded-3xl { border-radius: var(--radius-lg) !important; }
    .shadow-2xl, .shadow-xl, .shadow-lg { box-shadow: var(--shadow) !important; }

    /* ==========================================================
       15a. BUTTON SEMANTICS — colour must carry meaning
         blue   primary   (Create, Save, Apply, Search, Filter)
         green  success   (Export, Download, Approve)
         amber  warning   (high-impact but non-destructive)
         red    danger    (Delete, Void, Reject)
         white  secondary (Back, Cancel, Clear, Reset)
       Normalised to the exact tokens so the same action looks
       identical in every module.
       ========================================================== */
    button, a.inline-flex, a[class*="bg-"], input[type="submit"], .btn {
        font-size: 13px;
        font-weight: 500;
        line-height: 1.3;
        border-radius: var(--radius);
        transition: background-color .12s ease, border-color .12s ease;
    }
    /* One icon size and gap everywhere; icon sits before the label */
    button svg:not(.chevron):not(.nav-icon), a[class*="bg-"] svg, .btn svg {
        width: 14px; height: 14px; flex: none; vertical-align: -2px;
    }
    button i[class*="fa-"], a[class*="bg-"] i[class*="fa-"] {
        font-size: 12.5px; margin-right: 5px; width: 14px; text-align: center;
    }
    button.inline-flex, a.inline-flex { gap: 6px; }

    button[class*="bg-blue-"], a[class*="bg-blue-"],
    button[class*="bg-indigo-"], a[class*="bg-indigo-"],
    button[class*="bg-purple-"], a[class*="bg-purple-"] {
        background-color: var(--primary) !important; border: 1px solid var(--primary) !important;
    }
    button[class*="bg-blue-"]:hover, a[class*="bg-blue-"]:hover,
    button[class*="bg-indigo-"]:hover, a[class*="bg-indigo-"]:hover,
    button[class*="bg-purple-"]:hover, a[class*="bg-purple-"]:hover {
        background-color: var(--primary-hover) !important; border-color: var(--primary-hover) !important;
    }
    button[class*="bg-green-"], a[class*="bg-green-"],
    button[class*="bg-emerald-"], a[class*="bg-emerald-"],
    button[class*="bg-teal-"], a[class*="bg-teal-"] {
        background-color: var(--success) !important; border: 1px solid var(--success) !important;
    }
    button[class*="bg-green-"]:hover, a[class*="bg-green-"]:hover,
    button[class*="bg-emerald-"]:hover, a[class*="bg-emerald-"]:hover {
        background-color: #166534 !important; border-color: #166534 !important;
    }
    button[class*="bg-amber-"], a[class*="bg-amber-"],
    button[class*="bg-yellow-"], a[class*="bg-yellow-"],
    button[class*="bg-orange-"], a[class*="bg-orange-"] {
        background-color: var(--warning) !important; border: 1px solid var(--warning) !important;
    }
    button[class*="bg-red-"], a[class*="bg-red-"],
    button[class*="bg-rose-"], a[class*="bg-rose-"] {
        background-color: var(--danger) !important; border: 1px solid var(--danger) !important;
    }
    button[class*="bg-red-"]:hover, a[class*="bg-red-"]:hover {
        background-color: #991B1B !important; border-color: #991B1B !important;
    }

    /* ==========================================================
       15b. BUTTON SEMANTICS — corrections found in visual QA
       ========================================================== */
    /* "Clear" / "Reset" rendered as dark charcoal slabs because
       bg-gray-600 remaps to #374151. A cancel-style action is
       secondary, not a heavy filled button. */
    button[class*="bg-gray-6"], a[class*="bg-gray-6"],
    button[class*="bg-gray-5"], a[class*="bg-gray-5"],
    button[class*="bg-slate-6"], a[class*="bg-slate-6"] {
        background-color: #FFFFFF !important;
        color: var(--body) !important;
        border: 1px solid var(--line) !important;
    }
    button[class*="bg-gray-6"]:hover, a[class*="bg-gray-6"]:hover,
    button[class*="bg-gray-5"]:hover, a[class*="bg-gray-5"]:hover {
        background-color: #F9FAFB !important; border-color: #D1D5DB !important;
    }

    /* Saving a record is the primary action, not a "success" state.
       Green stays reserved for genuine success semantics (export,
       approve, paid). */
    button[type="submit"][class*="bg-green-"],
    input[type="submit"][class*="bg-green-"],
    button[type="submit"][class*="bg-emerald-"] {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #FFFFFF !important;
    }
    button[type="submit"][class*="bg-green-"]:hover,
    input[type="submit"][class*="bg-green-"]:hover {
        background-color: var(--primary-hover) !important; border-color: var(--primary-hover) !important;
    }

    /* Amber "evidence required" panels read as a warning, not a highlighter */
    div[class*="bg-yellow-"]:not(button):not(a),
    div[class*="bg-amber-"]:not(button):not(a) {
        background-color: #FFFBEB !important;
        border: 1px solid #FDE68A !important;
        color: #92400E !important;
    }

    /* ==========================================================
       15c. EMPTY AREAS
       A zero-record region should read as intentional, not as a
       broken gray slab.
       ========================================================== */
    .bg-gray-700:empty, div.bg-gray-700 > .text-center:only-child { color: var(--muted); }
    /* Legacy "no data" panels sitting on bg-gray-700/900 */
    div.bg-gray-700, div.bg-gray-900 { background-color: var(--surface) !important; }
    .bg-gray-800 div.bg-gray-900 { background-color: #FBFCFD !important; }
    table tbody tr td[colspan] {
        text-align: center; color: var(--muted); padding: 36px 16px !important; font-size: 13px;
    }

    /* Select2 — the searchable select must match the native inputs */
    .select2-container .select2-selection--single {
        height: 32px !important; border: 1px solid #D1D5DB !important;
        border-radius: var(--radius) !important; background: #fff !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 30px !important; padding-left: 10px !important;
        color: var(--body) !important; font-size: 13px !important;
    }
    .select2-container .select2-selection--single .select2-selection__arrow { height: 30px !important; }
    .select2-container--default .select2-selection--single .select2-selection__placeholder { color:#9CA3AF !important; }
    .select2-dropdown {
        border: 1px solid var(--line) !important; border-radius: var(--radius-lg) !important;
        box-shadow: 0 6px 20px rgba(16,24,40,.10) !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary) !important;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #D1D5DB !important; border-radius: var(--radius) !important; font-size: 13px !important;
    }

    /* ==========================================================
       16. FORM LAYOUT — short data should not span 1500px
       Inputs size to their content class, not the viewport.
       ========================================================== */
    .form-grid {
        display:grid; grid-template-columns:repeat(12, minmax(0,1fr));
        gap: var(--sp-3) var(--sp-4); align-items:start;
    }
    .col-2{grid-column:span 2}.col-3{grid-column:span 3}.col-4{grid-column:span 4}
    .col-6{grid-column:span 6}.col-8{grid-column:span 8}.col-12{grid-column:span 12}
    @media (max-width: 1024px){ .col-2,.col-3,.col-4{grid-column:span 6} }
    @media (max-width: 640px){ .form-grid > *{grid-column:span 12 !important} }

    /* Cap intrinsically short fields even in legacy full-width markup */
    input[type="date"], input[type="time"] { max-width: 190px; }
    input[type="number"] { max-width: 220px; }
    select:not([multiple]) { max-width: 340px; }
    .field-wide input, .field-wide select, .field-wide textarea, textarea { max-width: none; }
    /* Inside a grid/flex cell the cap must not fight the column */
    .form-grid input, .form-grid select, .filter-grid input, .filter-grid select { max-width: none; width: 100%; }

    /* ==========================================================
       17. CHECKBOX / RADIO / TOGGLE
       ========================================================== */
    input[type="checkbox"], input[type="radio"] {
        appearance:none; -webkit-appearance:none;
        width:15px; height:15px; flex:none; margin:0 6px 0 0;
        border:1px solid #9CA3AF; background:#fff; cursor:pointer;
        vertical-align:-2px; transition:background-color .12s, border-color .12s;
        padding:0 !important; max-width:none !important;
    }
    input[type="checkbox"]{ border-radius:3px; }
    input[type="radio"]{ border-radius:50%; }
    input[type="checkbox"]:checked, input[type="radio"]:checked {
        background-color:var(--primary); border-color:var(--primary);
    }
    input[type="checkbox"]:checked::after {
        content:''; display:block; width:4px; height:8px; margin:1px auto 0;
        border:solid #fff; border-width:0 2px 2px 0; transform:rotate(45deg);
    }
    input[type="radio"]:checked::after {
        content:''; display:block; width:5px; height:5px; margin:4px auto 0;
        border-radius:50%; background:#fff;
    }
    input[type="checkbox"]:focus-visible, input[type="radio"]:focus-visible {
        outline:2px solid var(--primary); outline-offset:2px;
    }
    input[type="checkbox"]:disabled, input[type="radio"]:disabled { background:#F3F4F6; border-color:#D1D5DB; cursor:not-allowed; }

    /* ==========================================================
       18. FILE UPLOAD
       ========================================================== */
    input[type="file"] { padding:5px 10px !important; font-size:13px !important; max-width:none; }
    input[type="file"]::file-selector-button {
        margin-right:10px; padding:5px 11px; border-radius:var(--radius);
        border:1px solid var(--line); background:#fff; color:var(--body);
        font-size:13px; font-weight:500; font-family:inherit; cursor:pointer;
    }
    input[type="file"]::file-selector-button:hover { background:#F9FAFB; border-color:#D1D5DB; }
    .dropzone {
        border:1px dashed #D1D5DB; border-radius:var(--radius-lg);
        background:#FCFCFD; padding:28px 20px; text-align:center;
        color:var(--muted); font-size:13px; transition:border-color .12s, background-color .12s;
    }
    .dropzone:hover, .dropzone.is-dragover { border-color:var(--primary); background:#F8FAFF; }
    .dropzone-title { font-size:14px; font-weight:500; color:var(--heading); margin-bottom:2px; }
    .dropzone-hint  { font-size:12px; color:var(--muted); }

    /* ==========================================================
       19. TABS
       ========================================================== */
    .tabs { display:flex; gap:2px; border-bottom:1px solid var(--line); margin-bottom:var(--sp-4); }
    .tab {
        padding:8px 13px; font-size:13px; font-weight:500; color:var(--muted);
        border:none; background:none; cursor:pointer; border-bottom:2px solid transparent;
        margin-bottom:-1px; transition:color .12s, border-color .12s;
    }
    .tab:hover { color:var(--heading); }
    .tab.active { color:var(--primary); border-bottom-color:var(--primary); }

    /* ==========================================================
       20. PAGINATION (Laravel's default markup + generic)
       ========================================================== */
    .pagination, nav[role="navigation"] .flex { gap:2px; }
    .pagination li a, .pagination li span,
    nav[role="navigation"] a, nav[role="navigation"] span[aria-current] {
        display:inline-flex; align-items:center; justify-content:center;
        min-width:30px; height:30px; padding:0 9px;
        border:1px solid var(--line); background:#fff; color:var(--body);
        font-size:13px; border-radius:var(--radius); text-decoration:none;
    }
    .pagination li a:hover, nav[role="navigation"] a:hover { background:#F9FAFB; border-color:#D1D5DB; }
    .pagination li.active span, nav[role="navigation"] span[aria-current="page"] span,
    nav[role="navigation"] span[aria-current="page"] {
        background:var(--primary) !important; border-color:var(--primary) !important; color:#fff !important;
    }
    .pagination li.disabled span, nav[role="navigation"] span:not([aria-current]) { color:#9CA3AF; background:#F9FAFB; }

    /* Sorting indicator */
    th[data-sort], th.sortable { cursor:pointer; user-select:none; }
    th[data-sort]:hover, th.sortable:hover { color:var(--heading) !important; }

    /* ==========================================================
       21. LOADING + FEEDBACK
       ========================================================== */
    .spinner {
        display:inline-block; width:14px; height:14px; border-radius:50%;
        border:2px solid rgba(255,255,255,.45); border-top-color:#fff;
        animation:sopod-spin .6s linear infinite; vertical-align:-2px;
    }
    .spinner-dark { border-color:#D1D5DB; border-top-color:var(--primary); }
    @keyframes sopod-spin { to { transform:rotate(360deg); } }
    .skeleton {
        background:linear-gradient(90deg,#F3F4F6 25%,#E9EBEF 37%,#F3F4F6 63%);
        background-size:400% 100%; animation:sopod-shimmer 1.3s ease infinite; border-radius:4px;
    }
    @keyframes sopod-shimmer { 0%{background-position:100% 50%} 100%{background-position:0 50%} }
    .is-loading { pointer-events:none; opacity:.65; }
    button:disabled, .btn-primary:disabled, .btn-secondary:disabled,
    a[aria-disabled="true"] { opacity:.55; cursor:not-allowed; }

    /* Validation */
    .has-error input, .has-error select, .has-error textarea,
    input.is-invalid, select.is-invalid, textarea.is-invalid,
    input[aria-invalid="true"] {
        border-color:var(--danger) !important; background:#FFFBFB !important;
    }
    .error-text, .invalid-feedback { font-size:12px; color:var(--danger); margin-top:4px; display:block; }
    .success-text { font-size:12px; color:var(--success); margin-top:4px; display:block; }

    /* Tooltip */
    [data-tooltip]{ position:relative; }
    [data-tooltip]:hover::after{
        content:attr(data-tooltip); position:absolute; bottom:calc(100% + 6px); left:50%;
        transform:translateX(-50%); white-space:nowrap; z-index:60;
        background:var(--surface); color:#fff; font-size:12px; font-weight:400;
        padding:4px 8px; border-radius:4px; pointer-events:none;
    }

    /* Breadcrumbs */
    .breadcrumb { display:flex; align-items:center; gap:6px; font-size:12px; color:var(--muted); margin-bottom:6px; }
    .breadcrumb a { color:var(--muted); text-decoration:none; }
    .breadcrumb a:hover { color:var(--primary); }

    /* ==========================================================
       22. MODAL / OVERLAY
       ========================================================== */
    .modal-overlay, div[class*="bg-black"][class*="bg-opacity"], div[class*="bg-gray-900"][class*="bg-opacity"] {
        background-color: rgba(17,24,39,.45) !important;
        backdrop-filter: blur(1px);
    }
    /* Any fixed, centred panel reads as a modal card */
    .fixed .bg-gray-800, .fixed .bg-white {
        border-radius: var(--radius-lg) !important;
        box-shadow: 0 10px 30px rgba(16,24,40,.14) !important;
        border: 1px solid var(--line) !important;
    }

    /* ==========================================================
       23. ANALYTICS SURFACES
       Charts sit on the same white workspace as everything else.
       ========================================================== */
    /* Two-level import navigation (Excel Import). Level 1 = category tabs,
       level 2 = the individual imports in that category. */
    .imp-cats {
        display: flex; flex-wrap: wrap; gap: 2px;
        border-bottom: 1px solid var(--line);
        padding: 0 var(--sp-6);
    }
    .imp-cat {
        padding: 10px 14px; font-size: 13px; font-weight: 500;
        color: var(--muted); background: none; border: none;
        border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer;
        transition: color .12s ease, border-color .12s ease; white-space: nowrap;
    }
    .imp-cat:hover { color: var(--heading); }
    .imp-cat.active { color: var(--primary); border-bottom-color: var(--primary); }
    .imp-cat[hidden] { display: none; }

    .imp-subs { padding: var(--sp-3) var(--sp-6) 0; }
    .imp-sub { display: flex; flex-wrap: wrap; gap: var(--sp-2); }
    .imp-sub[hidden] { display: none; }
    .imp-sub .tab-button {
        padding: 6px 12px !important; font-size: 13px; font-weight: 500;
        color: var(--body) !important; background: #fff !important;
        border: 1px solid var(--line) !important; border-radius: 999px !important;
        cursor: pointer; white-space: nowrap; transition: background-color .12s, border-color .12s, color .12s;
    }
    .imp-sub .tab-button:hover { background: #F9FAFB !important; border-color: #D1D5DB !important; }
    .imp-sub .tab-button.is-active {
        background: var(--primary) !important; border-color: var(--primary) !important;
        color: #FFFFFF !important;
    }
    .imp-sub .tab-button.is-destructive { color: var(--danger) !important; border-color: #FECACA !important; }
    .imp-sub .tab-button.is-destructive.is-active {
        background: var(--danger) !important; border-color: var(--danger) !important; color: #fff !important;
    }

    /* Report card — a link to a report, not a call to action. Used where a
       report was previously rendered as a large filled button. */
    .report-card {
        display: flex; align-items: center; justify-content: space-between; gap: var(--sp-4);
        width: 100%; text-align: left;
        background: var(--surface) !important;
        border: 1px solid var(--line) !important;
        border-radius: var(--radius-lg);
        padding: 14px 16px;
        cursor: pointer;
        transition: border-color .12s ease, background-color .12s ease;
    }
    .report-card:hover { border-color: #C7D2E4 !important; background: #F9FAFB !important; }
    .report-card-body { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .report-card-title { font-size: 14px; font-weight: 600; color: var(--heading); }
    .report-card-desc  { font-size: 12px; color: var(--muted); }
    .report-card-action {
        flex: none; font-size: 13px; font-weight: 500; color: var(--primary);
        padding: 5px 11px; border: 1px solid var(--line); border-radius: var(--radius); background: #fff;
    }
    .report-card:hover .report-card-action { border-color: var(--primary); }

    /* Toggle — compact inline switch for options like "Show Hidden DRs" */
    .toggle-row {
        display: inline-flex; align-items: center; gap: 10px;
        font-size: 13px; color: var(--body); cursor: pointer; user-select: none;
    }
    .toggle-row input[type="checkbox"] {
        appearance: none; -webkit-appearance: none;
        width: 34px; height: 19px; border-radius: 999px;
        background: #D1D5DB; border: none !important; position: relative;
        cursor: pointer; flex: none; margin: 0; transition: background-color .14s ease;
    }
    .toggle-row input[type="checkbox"]::after {
        content: ''; position: absolute; top: 2px; left: 2px;
        width: 15px; height: 15px; border-radius: 50%; background: #fff;
        border: none; transform: none;
        box-shadow: 0 1px 2px rgba(16,24,40,.2); transition: transform .14s ease;
    }
    .toggle-row input[type="checkbox"]:checked { background: var(--primary); }
    .toggle-row input[type="checkbox"]:checked::after { transform: translateX(15px); }
    .toggle-row input[type="checkbox"]:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }
    .toggle-hint { font-size: 12px; color: var(--muted); margin-left: 44px; display: block; }

    .chart-panel {
        background:var(--surface); border:1px solid var(--line);
        border-radius:var(--radius-lg); padding:var(--sp-4) var(--sp-6);
    }
    .chart-title { font-size:14px; font-weight:600; color:var(--heading); margin:0 0 2px; }
    .chart-sub   { font-size:12px; color:var(--muted); margin:0 0 var(--sp-4); }
    canvas { max-width:100%; }

    /* KPI accents: a 3px rail, never a saturated fill */
    .kpi-accent { border-left:3px solid var(--line); }
    .kpi-accent.is-primary{border-left-color:var(--primary)}
    .kpi-accent.is-success{border-left-color:var(--success)}
    .kpi-accent.is-warning{border-left-color:var(--warning)}
    .kpi-accent.is-danger {border-left-color:var(--danger)}

    /* ==========================================================
       24. OVERFLOW + SCROLLBARS
       ========================================================== */
    body { overflow-x:hidden; }
    .overflow-x-auto, .table-wrap { max-width:100%; }
    * { scrollbar-width:thin; scrollbar-color:#CBD5E1 transparent; }
    ::-webkit-scrollbar { width:10px; height:10px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:#D5DAE2; border-radius:6px; border:2px solid transparent; background-clip:content-box; }
    ::-webkit-scrollbar-thumb:hover { background:#B6BECB; background-clip:content-box; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration:.001ms !important; transition-duration:.001ms !important; }
    }

    @media print {
        #sidebar, #topbar { display:none !important; }
        body { background:#fff !important; }
        .card, .bg-gray-800 { box-shadow:none !important; border:1px solid #ddd !important; }
    }
</style>

{{-- ==============================================================
     ICON SPRITE — Lucide outline icons, 24x24, stroke-based.
     Inline so there is no CDN dependency and no FOUC. Referenced
     from the sidebar as <svg class="nav-icon"><use href="#i-x"/></svg>
     ============================================================== --}}
<script>
/* ------------------------------------------------------------------
   Empty-state filler — presentation only.
   Many list views render a table head and then nothing when there are
   no records, leaving a large blank area that reads as broken. This
   adds one standard "no records" row to tables that have a header but
   no body rows. It never removes or alters existing rows, and it
   withdraws itself the moment real rows appear, so tables populated by
   JavaScript are unaffected.
   ------------------------------------------------------------------ */
(function () {
    var MSG = 'No records found';
    var SUB = 'Records matching your current filters will appear here.';

    function cols(table) {
        var hr = table.tHead && table.tHead.rows[0];
        return hr ? Math.max(1, hr.cells.length) : 1;
    }

    function fill(table) {
        var tb = table.tBodies[0];
        if (!tb || tb.querySelector('tr')) return;
        if (tb.dataset.sopodFilled) return;
        var tr = document.createElement('tr');
        tr.className = 'sopod-empty-row';
        tr.innerHTML = '<td colspan="' + cols(table) + '">' +
            '<div class="empty-state-title">' + MSG + '</div>' +
            '<div class="empty-note">' + SUB + '</div></td>';
        tb.appendChild(tr);
        tb.dataset.sopodFilled = '1';

        new MutationObserver(function (muts, obs) {
            if (tb.querySelector('tr:not(.sopod-empty-row)')) {
                var e = tb.querySelector('.sopod-empty-row');
                if (e) e.remove();
                delete tb.dataset.sopodFilled;
                obs.disconnect();
            }
        }).observe(tb, { childList: true });
    }

    function scan() {
        document.querySelectorAll('table').forEach(function (t) {
            if (t.tHead && t.tBodies.length) fill(t);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { setTimeout(scan, 350); });
    } else {
        setTimeout(scan, 350);
    }
})();
</script>

<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
    <defs>
        <g id="lucide-base" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></g>
    </defs>
    <symbol id="i-dashboard" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/>
        <rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>
    </symbol>
    <symbol id="i-wallet" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h15a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5"/><path d="M18 12h.01"/>
    </symbol>
    <symbol id="i-clipboard" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="8" y="2" width="8" height="4" rx="1"/>
        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
        <path d="M8 11h8M8 15h5"/>
    </symbol>
    <symbol id="i-receipt" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M8 8h8M8 12h8M8 16h5"/>
    </symbol>
    <symbol id="i-package" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m7.5 4.3 9 5.1"/>
        <path d="M21 8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
        <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
    </symbol>
    <symbol id="i-file" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v5h5"/><path d="M8 13h8M8 17h8"/>
    </symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
        <path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>
    </symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
        <path d="M10 6h4M10 10h4M10 14h4M10 18h4"/>
    </symbol>
    <symbol id="i-warehouse" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35a2 2 0 0 1 1.26-1.85l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"/>
        <path d="M6 18h12v-6H6Z"/><path d="M6 14h12"/>
    </symbol>
    <symbol id="i-bird" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 7h.01"/><path d="M3.4 18H12a8 8 0 0 0 8-8V7a4 4 0 0 0-7.28-2.3L2 20"/>
        <path d="m20 7 2 .5-2 .5"/><path d="M10 18v3"/><path d="M14 17.75V21"/>
    </symbol>
    <symbol id="i-landmark" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 22h18"/><path d="M6 18v-7M10 18v-7M14 18v-7M18 18v-7"/><path d="m12 2 9 6H3Z"/>
    </symbol>
    <symbol id="i-truck" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
        <path d="M14 9h4l4 4v4a1 1 0 0 1-1 1h-1"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/><path d="M9 18h6"/>
    </symbol>
    <symbol id="i-repeat" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>
    </symbol>
    <symbol id="i-banknote" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
    </symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"/>
        <path d="M16 2v4M8 2v4M3 10h18"/><circle cx="17" cy="17" r="5"/><path d="M17 15v2l1 1"/>
    </symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 7v14"/>
        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3Z"/>
    </symbol>
    <symbol id="i-filepen" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h6"/><path d="M14 2v5h5"/>
        <path d="M21.4 13.6a2 2 0 0 0-2.8-2.8L14 15.4V19h3.6Z"/>
    </symbol>
    <symbol id="i-trending" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 7 13.5 15.5l-5-5L2 17"/><path d="M16 7h6v6"/>
    </symbol>
    <symbol id="i-folder" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
    </symbol>
    <symbol id="i-sheet" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v5h5"/>
        <path d="M8 13h3v3H8zM13 13h3v3h-3zM8 17h3M13 17h3"/>
    </symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </symbol>
    <symbol id="i-chevron" viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="m6 9 6 6 6-6"/>
    </symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 6h18M3 12h18M3 18h18"/>
    </symbol>
    {{-- Inline UI indicators. These restore meaning that the emoji sweep
         removed (back links, warnings, trend arrows, reject/edit actions). --}}
    <symbol id="i-arrow-left" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
    </symbol>
    <symbol id="i-arrow-right" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12h14M12 5l7 7-7 7"/>
    </symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 19V5M5 12l7-7 7 7"/>
    </symbol>
    <symbol id="i-arrow-down" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 5v14M19 12l-7 7-7-7"/>
    </symbol>
    <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
        <path d="M12 9v4M12 17h.01"/>
    </symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 6 6 18M6 6l12 12"/>
    </symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 6 9 17l-5-5"/>
    </symbol>
    <symbol id="i-pencil" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21.17 3.83a2.83 2.83 0 0 0-4 0L3 18v3h3L20.17 6.83a2.83 2.83 0 0 0 0-3Z"/>
    </symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 5v14M5 12h14"/>
    </symbol>
    <symbol id="i-minus" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12h14"/>
    </symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
    </symbol>
</svg>
