{{--
    Include this partial once per dashboard page.
    Usage: <x-charts />
    After including, call: renderChart(id, config) from inline <script> blocks.
--}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Global chart registry — tracks all charts on this page for resize/destroy
window._charts = window._charts || {};

function renderChart(canvasId, config) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    // Destroy previous instance if re-rendering
    if (window._charts[canvasId]) {
        window._charts[canvasId].destroy();
    }

    // Apply consistent defaults
    Chart.defaults.font.family = "'Inter', 'system-ui', sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6B7280';

    window._charts[canvasId] = new Chart(canvas, config);
}

// Palette — consistent colours across all charts
const PALETTE = {
    blue:   { bg: 'rgba(37,99,235,0.85)',   border: '#1D4ED8' },
    teal:   { bg: 'rgba(13,148,136,0.85)',  border: '#0F766E' },
    amber:  { bg: 'rgba(245,158,11,0.85)',  border: '#D97706' },
    red:    { bg: 'rgba(239,68,68,0.85)',   border: '#DC2626' },
    green:  { bg: 'rgba(34,197,94,0.85)',   border: '#16A34A' },
    purple: { bg: 'rgba(139,92,246,0.85)',  border: '#7C3AED' },
    gray:   { bg: 'rgba(107,114,128,0.85)', border: '#4B5563' },
    orange: { bg: 'rgba(249,115,22,0.85)',  border: '#EA580C' },
};

const MULTI_COLORS = [
    PALETTE.blue, PALETTE.teal, PALETTE.amber, PALETTE.green,
    PALETTE.purple, PALETTE.red, PALETTE.orange, PALETTE.gray,
];

// Standard chart options factories
function barOptions(yLabel = '', currency = false) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => currency
                        ? ' LKR ' + Number(ctx.raw).toLocaleString()
                        : ' ' + ctx.raw,
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { maxRotation: 0 } },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    callback: v => currency ? 'LKR ' + v.toLocaleString() : v,
                },
                title: { display: !!yLabel, text: yLabel, font: { size: 11 } }
            }
        }
    };
}

function doughnutOptions(legendPosition = 'right') {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: legendPosition,
                labels: { boxWidth: 12, padding: 12 }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.label + ': ' + ctx.raw
                }
            }
        },
        cutout: '65%',
    };
}

function lineOptions(yLabel = '', currency = false) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => currency
                        ? ' LKR ' + Number(ctx.raw).toLocaleString()
                        : ' ' + ctx.raw,
                }
            }
        },
        scales: {
            x: { grid: { display: false } },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    callback: v => currency ? 'LKR ' + v.toLocaleString() : v
                },
                title: { display: !!yLabel, text: yLabel, font: { size: 11 } }
            }
        },
        elements: {
            line: { tension: 0.35, borderWidth: 2 },
            point: { radius: 3, hoverRadius: 5 }
        }
    };
}
</script>