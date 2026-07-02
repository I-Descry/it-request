document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.dashboardData === 'undefined') return;

    const data = window.dashboardData;
    const ff = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
    const gc = 'rgba(156, 163, 175, 0.15)'; // Soft gray that works in both light and dark modes
    const base = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1f2937', titleFont: { size: 10, family: ff }, bodyFont: { size: 10, family: ff }, padding: 8, cornerRadius: 5 } },
        scales: { x: { grid: { display: false }, ticks: { font: { size: 9, family: ff }, color: '#9ca3af' } }, y: { grid: { color: gc }, ticks: { font: { size: 9, family: ff }, color: '#9ca3af', precision: 0 }, beginAtZero: true } }
    };

    // Donut Chart - Request Types
    const dc = ['#3b82f6','#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#ef4444','#14b8a6'];
    const donutEl = document.getElementById('donutChart');
    if (donutEl && data.requestTypes) {
        new Chart(donutEl, { 
            type: 'doughnut', 
            data: { 
                labels: data.requestTypes.keys, 
                datasets: [{ 
                    data: data.requestTypes.values, 
                    backgroundColor: dc.slice(0, data.requestTypes.keys.length), 
                    borderWidth: 2, 
                    borderColor: '#fff', 
                    hoverOffset: 4 
                }] 
            }, 
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '55%', 
                onClick: (event, elements, chart) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        window.location.href = `/tickets?request_type=${encodeURIComponent(label)}`;
                    }
                },
                onHover: (event, elements, chart) => {
                    event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                },
                plugins: { 
                    legend: { position: 'right', labels: { font: { size: 10, family: ff }, padding: 10, boxWidth: 12, color: '#9ca3af' } }, 
                    tooltip: { 
                        ...base.plugins.tooltip, 
                        callbacks: { 
                            label: function(c) { 
                                const t = c.dataset.data.reduce((a,b)=>a+b,0); 
                                return c.label+': '+c.parsed+' tickets ('+(t>0?((c.parsed/t)*100).toFixed(1):'0.0')+'%)'; 
                            } 
                        } 
                    } 
                } 
            } 
        });
    }

    // Technician Performance - Horizontal Bar
    const techEl = document.getElementById('techChart');
    if (techEl && data.techPerformance) {
        const datasets = [];

        const sumResolved = data.techPerformance.resolved.reduce((a, b) => a + b, 0);
        if (sumResolved > 0) {
            datasets.push({ label: 'Resolved', data: data.techPerformance.resolved, backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.6 });
        }

        const sumInProgress = data.techPerformance.in_progress.reduce((a, b) => a + b, 0);
        if (sumInProgress > 0) {
            datasets.push({ label: 'In Progress', data: data.techPerformance.in_progress, backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.6 });
        }

        const sumEscalated = data.techPerformance.escalated.reduce((a, b) => a + b, 0);
        if (sumEscalated > 0) {
            datasets.push({ label: 'Escalated', data: data.techPerformance.escalated, backgroundColor: '#f59e0b', borderRadius: 4, barPercentage: 0.6 });
        }

        new Chart(techEl, { 
            type: 'bar', 
            data: { 
                labels: data.techPerformance.labels, 
                datasets: datasets
            }, 
            options: { 
                ...base, 
                indexAxis: 'y', 
                plugins: { 
                    ...base.plugins, 
                    legend: { display: true, position: 'top', labels: { font: { size: 10, family: ff }, boxWidth: 12, padding: 8, color: '#9ca3af' } }, 
                    tooltip: { 
                        ...base.plugins.tooltip, 
                        callbacks: { label: function(c) { return c.dataset.label + ': ' + c.parsed.x + ' tickets'; } } 
                    } 
                }, 
                scales: { 
                    x: { ...base.scales.y, grid: { color: gc } }, 
                    y: { ...base.scales.x, grid: { display: false } } 
                } 
            } 
        });
    }
    // AJAX Pagination for Recent Tickets
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.dk-pagination a');
        if (pageLink) {
            e.preventDefault();
            const url = pageLink.href;
            const container = document.getElementById('recent-tickets-container');
            if (!container) return;

            
            container.style.pointerEvents = 'none';
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('recent-tickets-container');
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                }
                container.style.pointerEvents = 'auto';
            })
            .catch(() => {
                window.location.href = url;
            });
        }
    });
});


