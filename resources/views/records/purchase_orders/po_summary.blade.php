@extends('layouts.app')

@section('title', 'PO Summary Monitor')

@section('content')

<style>
/* ==================================================================
   PO Summary — presentation only.
   Rewritten 2026-08-15 to consume the global SOPOD tokens. Every
   selector and class name is preserved exactly, so the Blade markup
   and JavaScript are untouched; only colours, spacing and type change.
   Previously this file defined its own dark palette (#1f2937 / #2d3748)
   and five saturated KPI accents, which is why the module looked like
   a different product.
   ================================================================== */
.po-summary-wrap { color: var(--body); }

.mono { font-variant-numeric: tabular-nums; font-feature-settings: "tnum"; }

/* ---- KPI cards: white surface, 3px semantic rail, no fills ---- */
.kpi-card {
    position: relative;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 14px 16px 14px 17px;
    overflow: hidden;
    transition: border-color .12s ease;
}
.kpi-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--line);
}
.kpi-card:hover { border-color: #D8DDE5; }
.kpi-card.accent-blue::before   { background: var(--primary); }
.kpi-card.accent-green::before  { background: var(--success); }
.kpi-card.accent-amber::before  { background: var(--warning); }
.kpi-card.accent-rose::before   { background: var(--danger); }
.kpi-card.accent-violet::before { background: #6D28D9; }

.kpi-value {
    font-size: 26px; font-weight: 600; color: var(--heading);
    line-height: 1.15; margin-top: 4px;
    font-variant-numeric: tabular-nums;
}
.kpi-label { font-size: 12px; font-weight: 500; color: var(--muted); }
.kpi-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }

/* ---- Status pills: identical vocabulary to the global badges ---- */
.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 2px 8px; border-radius: 999px;
    font-size: 12px; font-weight: 500; line-height: 1.6; white-space: nowrap;
}
.status-pill::before { content:''; width:6px; height:6px; border-radius:50%; background:currentColor; flex:none; }
.status-PAID            { background:#DCFCE7; color:#15803D; }
.status-UNPAID          { background:#FEF3C7; color:#B45309; }
.status-FOR_DEPOSIT     { background:#DBEAFE; color:#1D4ED8; }
.status-FOR_COLLECTION  { background:#DBEAFE; color:#1D4ED8; }
.status-HOLD            { background:#F3F4F6; color:#4B5563; }
.status-CANCEL          { background:#F3F4F6; color:#6B7280; }
.status-REFUNDED        { background:#FEE2E2; color:#B91C1C; }

/* ---- Panels ---- */
.panel {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
}
.panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-bottom: 1px solid var(--line);
}
.panel-title { font-size: 14px; font-weight: 600; color: var(--heading); margin: 0; }

/* ---- Horizontal bar rows ---- */
.bar-row {
    display: flex; align-items: center; gap: 12px;
    padding: 8px 20px; border-bottom: 1px solid var(--line);
}
.bar-row:last-child { border-bottom: none; }
.bar-row:hover { background: #F9FAFB; }
.bar-track { flex: 1; height: 6px; background: #F1F3F7; border-radius: 3px; overflow: hidden; min-width: 60px; }
.bar-fill  { height: 100%; background: var(--primary); border-radius: 3px; }
.bar-label { font-size: 13px; color: var(--body); min-width: 0; }
.bar-amount { font-size: 13px; font-weight: 500; color: var(--heading); font-variant-numeric: tabular-nums; white-space: nowrap; }

/* ---- Table: matches the global table system ---- */
.po-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.po-table thead th {
    background: #F9FAFB; color: var(--muted);
    font-size: 12px; font-weight: 600; letter-spacing: .03em; text-transform: uppercase;
    padding: 8px 12px; text-align: left; white-space: nowrap;
    border-bottom: 1px solid var(--line);
    position: sticky; top: 0; z-index: 5;
}
.po-table tbody tr { border-bottom: 1px solid var(--line); }
.po-table tbody tr:hover { background: #F9FAFB; }
.po-table tbody td { padding: 8px 12px; color: var(--body); vertical-align: middle; }
.po-table tbody td.mono-col { font-variant-numeric: tabular-nums; text-align: right; white-space: nowrap; }

/* ---- Controls: same height/radius/focus as every other input ---- */
.filter-select, .search-input {
    background: #fff; border: 1px solid #D1D5DB; color: var(--body);
    border-radius: var(--radius); padding: 6px 10px; font-size: 13px;
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; transition: border-color .12s ease, box-shadow .12s ease;
}
.filter-select:focus, .search-input:focus {
    outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.11);
}
.search-input::placeholder { color: #9CA3AF; }

/* ---- Company tabs: same pattern as the global .tabs ---- */
.company-tab {
    padding: 8px 13px; font-size: 13px; font-weight: 500; color: var(--muted);
    background: none; border: none; border-bottom: 2px solid transparent;
    margin-bottom: -1px; cursor: pointer; transition: color .12s ease, border-color .12s ease;
}
.company-tab:hover { color: var(--heading); }
.company-tab.active { color: var(--primary); border-bottom-color: var(--primary); }

/* ---- Donut ---- */
.donut-wrap { display: flex; align-items: center; gap: 20px; padding: 16px 20px; flex-wrap: wrap; }
.donut-svg { flex: none; }
.donut-legend { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
.donut-legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--body); }
.donut-dot { width: 8px; height: 8px; border-radius: 2px; flex: none; }

/* ---- Status breakdown rows ---- */
.status-row { display: flex; align-items: center; gap: 12px; padding: 8px 20px; border-bottom: 1px solid var(--line); }
.status-row:last-child { border-bottom: none; }
.status-name { font-size: 13px; color: var(--body); min-width: 120px; }
.status-bar-wrap { flex: 1; height: 6px; background: #F1F3F7; border-radius: 3px; overflow: hidden; min-width: 60px; }
.status-count-num  { font-size: 13px; font-weight: 500; color: var(--heading); font-variant-numeric: tabular-nums; min-width: 44px; text-align: right; }
.status-amount-num { font-size: 13px; color: var(--muted); font-variant-numeric: tabular-nums; min-width: 100px; text-align: right; white-space: nowrap; }

/* ---- Scroll container ---- */
.table-scroll { overflow: auto; max-height: 560px; }
.table-scroll::-webkit-scrollbar { width: 10px; height: 10px; }
.table-scroll::-webkit-scrollbar-track { background: transparent; }
.table-scroll::-webkit-scrollbar-thumb { background: #D5DAE2; border-radius: 6px; border: 2px solid transparent; background-clip: content-box; }

/* ---- Section badge ---- */
.section-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase;
    color: var(--muted);
}
.section-badge::before { content: ''; width: 3px; height: 12px; background: var(--primary); border-radius: 2px; }

/* ---- Motion: one quiet fade, no staggered entrance choreography ---- */
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(3px); } to { opacity: 1; transform: none; } }
.kpi-card, .panel { animation: fadeSlideUp .18s ease both; }
.kpi-card:nth-child(1), .kpi-card:nth-child(2), .kpi-card:nth-child(3),
.kpi-card:nth-child(4), .kpi-card:nth-child(5) { animation-delay: 0s; }

.shimmer { background: linear-gradient(90deg,#F3F4F6 25%,#E9EBEF 37%,#F3F4F6 63%); background-size: 400% 100%; animation: shimmer 1.3s ease infinite; }
@keyframes shimmer { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }

@media (prefers-reduced-motion: reduce) { .kpi-card, .panel { animation: none; } }
</style>

<div class="po-summary-wrap" x-data="poSummary()" x-init="init()">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-white font-semibold text-xl tracking-tight">PO Summary Monitor</h1>
            <p class="text-gray-300 text-xs mt-0.5 mono">{{ now()->format('l, F j Y · H:i') }} · Real-time snapshot</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Company filter tabs --}}
            <div class="flex items-center gap-1 bg-gray-900 border border-gray-800 rounded-md p-1">
                <button class="company-tab" :class="{ active: activeCompany === 'all' }" @click="setCompany('all')">All</button>
                <button class="company-tab" :class="{ active: activeCompany === 'NBC' }" @click="setCompany('NBC')">NBC</button>
                <button class="company-tab" :class="{ active: activeCompany === 'PMAI' }" @click="setCompany('PMAI')">PMAI</button>
                <button class="company-tab" :class="{ active: activeCompany === 'PARI' }" @click="setCompany('PARI')">PARI</button>
            </div>
            <button @click="refreshData()" class="filter-select flex items-center gap-1.5" title="Refresh">
                <i class="fas fa-sync-alt text-xs" :class="{ 'fa-spin': loading }"></i>
                <span>Refresh</span>
            </button>
        </div>
    </div>

    {{-- ── KPI Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        <div class="kpi-card accent-blue">
            <p class="kpi-label">Total PO Value</p>
            <p class="kpi-value" x-text="formatPHP(kpis.totalPOAmount)">—</p>
            <p class="kpi-sub" x-text="kpis.totalPOCount + ' purchase orders'">—</p>
        </div>
        <div class="kpi-card accent-green">
            <p class="kpi-label">Total Invoiced</p>
            <p class="kpi-value" x-text="formatPHP(kpis.totalInvoiceAmount)">—</p>
            <p class="kpi-sub" x-text="formatPct(kpis.totalInvoiceAmount, kpis.totalPOAmount) + '% of PO value'">—</p>
        </div>
        <div class="kpi-card accent-rose">
            <p class="kpi-label">Outstanding Balance</p>
            <p class="kpi-value" x-text="formatPHP(kpis.totalPOAmount - kpis.totalInvoiceAmount)">—</p>
            <p class="kpi-sub">uninvoiced amount</p>
        </div>
        <div class="kpi-card accent-amber">
            <p class="kpi-label">Unpaid / For Collection</p>
            <p class="kpi-value" x-text="formatPHP(kpis.unpaidAmount)">—</p>
            <p class="kpi-sub" x-text="kpis.unpaidCount + ' orders pending'">—</p>
        </div>
        <div class="kpi-card accent-violet">
            <p class="kpi-label">Active Suppliers</p>
            <p class="kpi-value mono" x-text="kpis.supplierCount">—</p>
            <p class="kpi-sub" x-text="kpis.categoryCount + ' categories'">—</p>
        </div>
    </div>

    {{-- ── Middle Row: Status Breakdown + Company Split + Category ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">

        {{-- Status Breakdown --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Payment Status</span>
                <span class="mono" style="font-size:0.65rem; color:#9ca3af;" x-text="kpis.totalPOCount + ' total POs'"></span>
            </div>
            <template x-for="s in statuses" :key="s.key">
                <div class="status-row">
                    <div class="status-name">
                        <span class="status-pill" :class="'status-' + s.key.replace(/ /g,'_')" x-text="s.label"></span>
                    </div>
                    <div class="status-bar-wrap">
                        <div class="bar-fill" :style="'width:' + pct(s.amount, kpis.totalPOAmount) + '%; background:' + s.color"></div>
                    </div>
                    <div class="status-count-num" x-text="s.count"></div>
                    <div class="status-amount-num" x-text="formatPHP(s.amount)"></div>
                </div>
            </template>
        </div>

        {{-- Company Split --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">By Company</span>
            </div>
            <div class="donut-wrap" x-show="!loading">
                <svg class="donut-svg" width="100" height="100" viewBox="0 0 42 42">
                    <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#e5e7eb" stroke-width="5"/>
                    <template x-for="(seg, i) in donutSegments" :key="i">
                        <circle
                            cx="21" cy="21" r="15.915"
                            fill="transparent"
                            :stroke="seg.color"
                            stroke-width="5"
                            :stroke-dasharray="seg.dash"
                            :stroke-dashoffset="seg.offset"
                        />
                    </template>
                </svg>
                <div class="donut-legend">
                    <template x-for="c in companies" :key="c.key">
                        <div class="donut-legend-item">
                            <span class="donut-dot" :style="'background:' + c.color"></span>
                            <span x-text="c.key"></span>
                            <span class="mono ml-auto pl-3" style="color:#4b5563; font-size:0.68rem;" x-text="formatPHP(c.amount)"></span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="p-4" x-show="loading">
                <div class="shimmer h-20 w-full"></div>
            </div>
        </div>

        {{-- Top Categories --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Top Categories</span>
            </div>
            <template x-for="cat in topCategories" :key="cat.name">
                <div class="bar-row">
                    <span class="bar-label" x-text="cat.name"></span>
                    <div class="bar-track">
                        <div class="bar-fill" :style="'width:' + pct(cat.amount, topCategories[0].amount) + '%; background: #FFFFFF;'"></div>
                    </div>
                    <span class="bar-amount" x-text="formatPHP(cat.amount)"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- ── Bottom Row: Top Suppliers + Recent POs Table ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

        {{-- Top Suppliers --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Top Suppliers</span>
                <span class="mono" style="font-size:0.65rem;color:#9ca3af;">by PO amount</span>
            </div>
            <template x-for="(sup, i) in topSuppliers" :key="sup.name">
                <div class="bar-row">
                    <span class="bar-label">
                        <span class="mono" style="color:#9ca3af;" x-text="(i+1).toString().padStart(2,'0') + '. '"></span>
                        <span x-text="sup.name"></span>
                    </span>
                    <div class="bar-track">
                        <div class="bar-fill" :style="'width:' + pct(sup.amount, topSuppliers[0].amount) + '%; background: #8b5cf6;'"></div>
                    </div>
                    <span class="bar-amount" x-text="formatPHP(sup.amount)"></span>
                </div>
            </template>
        </div>

        {{-- Recent POs Table --}}
        <div class="panel lg:col-span-2">
            <div class="panel-header">
                <span class="panel-title">Recent Purchase Orders</span>
                <div class="flex items-center gap-2">
                    <select class="filter-select" x-model="filterStatus" @change="applyFilters()">
                        <option value="">All Statuses</option>
                        <option value="PAID">PAID</option>
                        <option value="UNPAID">UNPAID</option>
                        <option value="FOR DEPOSIT">FOR DEPOSIT</option>
                        <option value="FOR COLLECTION">FOR COLLECTION</option>
                        <option value="HOLD">HOLD</option>
                        <option value="CANCEL">CANCEL</option>
                        <option value="REFUNDED">REFUNDED</option>
                    </select>
                    <select class="filter-select" x-model="filterCategory" @change="applyFilters()">
                        <option value="">All Categories</option>
                        <option value="Day-Old-Chick">Day-Old-Chick</option>
                        <option value="Feeds">Feeds</option>
                        <option value="Vitamins">Vitamins</option>
                        <option value="Vaccines - Farm">Vaccines - Farm</option>
                        <option value="Farm Supplies">Farm Supplies</option>
                        <option value="Repair and Maintenance">Repair and Maintenance</option>
                        <option value="Fixed Asset">Fixed Asset</option>
                        <option value="Office Supplies">Office Supplies</option>
                    </select>
                    <input type="text" class="search-input" placeholder="Search PO / supplier…" x-model="searchQuery" @input="applyFilters()">
                </div>
            </div>
            <div class="table-scroll">
                <table class="po-table">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>GL Type</th>
                            <th>Company</th>
                            <th>PO Amount</th>
                            <th>Invoice Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="filteredPOs.length === 0">
                            <tr>
                                <td colspan="9" class="text-center py-8 text-gray-300 mono text-xs">No records match your filters.</td>
                            </tr>
                        </template>
                        <template x-for="po in filteredPOs.slice(0, 60)" :key="po.po_number + po.supplier">
                            <tr>
                                <td class="mono-col" x-text="po.po_number"></td>
                                <td class="mono-col" x-text="po.date"></td>
                                <td x-text="po.supplier" style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></td>
                                <td x-text="po.category" style="font-size:0.72rem; color:#9ca3af;"></td>
                                <td x-text="po.gl_type" style="font-size:0.7rem; color:#6b7280;"></td>
                                <td>
                                    <span class="mono" style="font-size:0.68rem; padding:0.1rem 0.4rem; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:3px; color:#60a5fa;" x-text="po.company"></span>
                                </td>
                                <td class="mono-col text-right" x-text="formatPHP(po.po_amount)"></td>
                                <td class="mono-col text-right" x-text="formatPHP(po.invoice_amount)"></td>
                                <td>
                                    <span class="status-pill" :class="'status-' + po.status.replace(/ /g,'_')" x-text="po.status"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="panel-header" style="border-top:1px solid #e5e7eb; border-bottom:none; justify-content:flex-end;">
                <span class="mono" style="font-size:0.65rem; color:#9ca3af;" x-text="'Showing ' + Math.min(filteredPOs.length, 60) + ' of ' + filteredPOs.length + ' records'"></span>
            </div>
        </div>

    </div>
</div>

{{-- ── Alpine.js data ── --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function poSummary() {
    return {
        loading: true,
        activeCompany: 'all',
        filterStatus: '',
        filterCategory: '',
        searchQuery: '',
        allPOs: [],
        filteredPOs: [],

        kpis: {
            totalPOAmount: 0,
            totalInvoiceAmount: 0,
            totalPOCount: 0,
            unpaidAmount: 0,
            unpaidCount: 0,
            supplierCount: 0,
            categoryCount: 0,
        },

        statuses: [],
        companies: [],
        topCategories: [],
        topSuppliers: [],
        donutSegments: [],

        async init() {
            await this.fetchData();
        },

        async fetchData() {
            this.loading = true;
            try {
                const params = this.activeCompany !== 'all' ? '?company=' + this.activeCompany : '';
                const res = await fetch(`{{ route('po_summary.api_data') }}${params}`);
                const data = await res.json();
                this.processData(data);
            } catch (e) {
                console.error('PO Summary fetch error:', e);
                this.processData({ pos: [], summary: {} });
            }
            this.loading = false;
        },

        processData(data) {
            this.allPOs = data.pos || [];
            this.applyFilters();
            this.buildKPIs();
            this.buildStatuses();
            this.buildCompanies();
            this.buildCategories();
            this.buildSuppliers();
            this.buildDonut();
            // Animate bars after render
            this.$nextTick(() => this.animateBars());
        },

        applyFilters() {
            let result = [...this.allPOs];
            if (this.filterStatus)   result = result.filter(p => p.status === this.filterStatus);
            if (this.filterCategory) result = result.filter(p => p.category === this.filterCategory);
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(p =>
                    (p.po_number||'').toLowerCase().includes(q) ||
                    (p.supplier||'').toLowerCase().includes(q)
                );
            }
            if (this.activeCompany !== 'all') result = result.filter(p => p.company === this.activeCompany);
            this.filteredPOs = result;
        },

        buildKPIs() {
    const pos = this.activeCompany === 'all' ? this.allPOs
        : this.allPOs.filter(p => p.company === this.activeCompany);

    this.kpis.totalPOAmount      = pos.reduce((s,p) => s + (p.po_amount||0), 0);
    this.kpis.totalInvoiceAmount = pos.reduce((s,p) => s + (p.invoice_amount||0), 0);
    this.kpis.totalPOCount       = pos.length;

    // Unpaid: use po_amount for UNPAID (no invoice), invoice_amount for invoiced-but-unpaid
    const unpaid = pos.filter(p => ['UNPAID','FOR COLLECTION','FOR DEPOSIT'].includes(p.status));
    this.kpis.unpaidAmount = unpaid.reduce((s,p) => {
        if (p.status === 'UNPAID') return s + (p.po_amount||0);
        return s + (p.invoice_amount||0);
    }, 0);
    this.kpis.unpaidCount  = unpaid.length;

    this.kpis.supplierCount  = new Set(pos.map(p => p.supplier)).size;
    this.kpis.categoryCount  = new Set(pos.map(p => p.category)).size;
},

        buildStatuses() {
            const defs = [
                { key: 'PAID',           label: 'PAID',           color: '#22c55e' },
                { key: 'UNPAID',         label: 'UNPAID',         color: '#f43f5e' },
                { key: 'FOR_DEPOSIT',    label: 'FOR DEPOSIT',    color: '#60a5fa' },
                { key: 'FOR_COLLECTION', label: 'FOR COLLECTION', color: '#a78bfa' },
                { key: 'HOLD',           label: 'HOLD',           color: '#fbbf24' },
                { key: 'REFUNDED',       label: 'REFUNDED',       color: '#2dd4bf' },
                { key: 'CANCEL',         label: 'CANCEL',         color: '#6b7280' },
            ];
            const pos = this.activeCompany === 'all' ? this.allPOs : this.allPOs.filter(p => p.company === this.activeCompany);
            this.statuses = defs.map(d => {
                const rows = pos.filter(p => p.status === d.label);
                return { ...d, count: rows.length, amount: rows.reduce((s,p)=>s+(p.po_amount||0),0) };
            }).filter(d => d.count > 0);
        },

        buildCompanies() {
            const companyDefs = [
                { key: 'NBC',  color: '#3b82f6' },
                { key: 'PMAI', color: '#22c55e' },
                { key: 'PARI', color: '#f59e0b' },
            ];
            this.companies = companyDefs.map(c => ({
                ...c,
                amount: this.allPOs.filter(p => p.company === c.key).reduce((s,p)=>s+(p.po_amount||0),0),
                count:  this.allPOs.filter(p => p.company === c.key).length,
            })).filter(c => c.count > 0);
        },

        buildDonut() {
            const total = this.companies.reduce((s,c)=>s+c.amount,0);
            const circumference = 100; // 2π × r = 2π × 15.915 ≈ 100
            let offset = 0;
            this.donutSegments = this.companies.map(c => {
                const fraction = total > 0 ? c.amount / total : 0;
                const dash = fraction * circumference;
                const seg = { color: c.color, dash: `${dash} ${circumference - dash}`, offset: -offset };
                offset += dash;
                return seg;
            });
        },

        buildCategories() {
            const pos = this.activeCompany === 'all' ? this.allPOs : this.allPOs.filter(p => p.company === this.activeCompany);
            const map = {};
            pos.forEach(p => {
                if (!p.category) return;
                map[p.category] = (map[p.category]||0) + (p.po_amount||0);
            });
            this.topCategories = Object.entries(map)
                .map(([name,amount]) => ({name,amount}))
                .sort((a,b) => b.amount-a.amount)
                .slice(0,8);
        },

        buildSuppliers() {
            const pos = this.activeCompany === 'all' ? this.allPOs : this.allPOs.filter(p => p.company === this.activeCompany);
            const map = {};
            pos.forEach(p => {
                if (!p.supplier) return;
                map[p.supplier] = (map[p.supplier]||0) + (p.po_amount||0);
            });
            this.topSuppliers = Object.entries(map)
                .map(([name,amount]) => ({name,amount}))
                .sort((a,b) => b.amount-a.amount)
                .slice(0,8);
        },

        animateBars() {
            document.querySelectorAll('.bar-fill').forEach(el => {
                const target = el.style.width;
                el.style.width = '0%';
                setTimeout(() => { el.style.width = target; }, 50);
            });
        },

        setCompany(c) {
            this.activeCompany = c;
            this.fetchData();
        },

        refreshData() { this.fetchData(); },

        formatPHP(val) {
    if (!val && val !== 0) return '—';
    return '₱' + val.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
},

        formatPct(a, b) {
            if (!b) return '0';
            return ((a / b) * 100).toFixed(1);
        },

        pct(val, max) {
            if (!max) return 0;
            return Math.max(2, (val / max) * 100);
        },
    };
}
</script>

@endsection