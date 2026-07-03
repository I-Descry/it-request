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

    // Donut Chart - Request Types (Harmonious & High Contrast Tailwind Palette)
    const dc = [
        '#3b82f6', // Blue
        '#f59e0b', // Amber
        '#10b981', // Emerald
        '#ec4899', // Pink
        '#06b6d4', // Cyan
        '#f97316', // Orange
        '#8b5cf6', // Violet
        '#84cc16', // Lime
        '#f43f5e', // Rose
        '#14b8a6', // Teal
        '#a855f7', // Purple
        '#eab308', // Yellow
        '#0ea5e9', // Sky
        '#ef4444', // Red
        '#22c55e', // Green
        '#d946ef', // Fuchsia
        '#6366f1', // Indigo
        '#64748b'  // Slate
    ];
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
                    legend: { 
                        position: 'right', 
                        labels: { 
                            font: { size: 10, family: ff }, 
                            padding: 10, 
                            boxWidth: 12, 
                            color: '#9ca3af',
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        const meta = chart.getDatasetMeta(0);
                                        const style = meta.controller.getStyle(i);
                                        const isHidden = !chart.getDataVisibility(i);

                                        return {
                                            text: label,
                                            fillStyle: isHidden ? '#374151' : style.backgroundColor, // tailwind gray-700
                                            strokeStyle: isHidden ? '#1f2937' : style.borderColor, // tailwind gray-800
                                            lineWidth: style.borderWidth,
                                            hidden: isHidden,
                                            fontColor: isHidden ? '#4b5563' : '#9ca3af', // tailwind gray-600
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        },
                        onClick: function(e, legendItem, legend) {
                            // Default Chart.js behavior: hide the slice
                            const index = legendItem.index;
                            const chart = legend.chart;
                            const label = chart.data.labels[index];
                            
                            // Get current url params
                            const urlParams = new URLSearchParams(window.location.search);
                            let excludes = urlParams.getAll('exclude[]');
                            
                            if (excludes.includes(label)) {
                                excludes = excludes.filter(item => item !== label);
                            } else {
                                excludes.push(label);
                            }
                            
                            urlParams.delete('exclude[]');
                            excludes.forEach(ex => urlParams.append('exclude[]', ex));

                            // Update URL in browser history without reloading
                            const newUrl = '/dashboard?' + urlParams.toString();
                            window.history.pushState({path: newUrl}, '', newUrl);
                            
                            // Perform AJAX fetch
                            fetch(newUrl, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Update KPIs
                                document.getElementById('kpi-total-val').innerText = data.totalActive;
                                document.getElementById('kpi-prog-val').innerText = data.byStatus['In Progress'] || 0;
                                document.getElementById('kpi-prog-sub').innerText = data.totalActive > 0 ? ((data.byStatus['In Progress'] || 0) / data.totalActive * 100).toFixed(1) + '%' : '0.0%';
                                
                                document.getElementById('kpi-esc-val').innerText = data.byStatus['Escalated'] || 0;
                                document.getElementById('kpi-esc-sub').innerText = data.totalActive > 0 ? ((data.byStatus['Escalated'] || 0) / data.totalActive * 100).toFixed(1) + '%' : '0.0%';
                                
                                document.getElementById('kpi-res-val').innerText = data.byStatus['Resolved'] || 0;
                                document.getElementById('kpi-res-sub').innerText = data.totalActive > 0 ? ((data.byStatus['Resolved'] || 0) / data.totalActive * 100).toFixed(1) + '%' : '0.0%';
                                
                                document.getElementById('kpi-not-val').innerText = data.byStatus['Not Complete'] || 0;
                                document.getElementById('kpi-not-sub').innerText = data.totalActive > 0 ? ((data.byStatus['Not Complete'] || 0) / data.totalActive * 100).toFixed(1) + '%' : '0.0%';

                                // Update Request Volume
                                const volumeHtml = data.metrics.map((metric, idx) => `
                                    <div style="display: flex; justify-content: space-between; align-items: center; ${idx < data.metrics.length - 1 ? 'border-bottom: 1px solid var(--border-color); padding-bottom: 8px;' : 'padding-bottom: 4px;'}">
                                        <span style="font-weight: 600; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">${metric.label}</span>
                                        <div style="font-size: 1.1rem; color: ${metric.color}; font-weight: 700;">${metric.value}</div>
                                    </div>
                                `).join('');
                                document.getElementById('volume-container').innerHTML = volumeHtml;

                                // Update HTML partials
                                document.getElementById('requestors-container').innerHTML = data.topRequestors;
                                document.getElementById('recent-tickets-container').innerHTML = data.recentTickets;

                                // Update Tech Chart
                                if (window.techChartInstance && data.techPerformance) {
                                    // Map new tech performance data to the existing chart labels
                                    const tL = window.techChartInstance.data.labels;
                                    const tR = [];
                                    const tI = [];
                                    const tE = [];
                                    
                                    tL.forEach(name => {
                                        let found = null;
                                        // Try to find the tech in the new data by mapping back the names
                                        const keyMap = {'Tristan Railey Tan': 'IT03', 'John Paul Villacorta': 'IT04'};
                                        const key = keyMap[name] || name;
                                        if (data.techPerformance[key]) {
                                            found = data.techPerformance[key];
                                        }
                                        
                                        tR.push(found ? found.resolved : 0);
                                        tI.push(found ? found.in_progress : 0);
                                        tE.push(found ? found.escalated : 0);
                                    });

                                    window.techChartInstance.data.datasets[0].data = tR;
                                    window.techChartInstance.data.datasets[1].data = tI;
                                    window.techChartInstance.data.datasets[2].data = tE;
                                    window.techChartInstance.update();
                                }
                            });

                            // Let Chart.js hide the slice
                            chart.toggleDataVisibility(index);
                            chart.update();
                        }
                    }, 
                    tooltip: { 
                        ...base.plugins.tooltip, 
                        callbacks: { 
                            label: function(c) { 
                                const chart = c.chart;
                                let t = 0;
                                // Only sum the visible data points for accurate percentages!
                                c.dataset.data.forEach((val, i) => {
                                    if (chart.getDataVisibility(i)) t += val;
                                });
                                return c.label+': '+c.parsed+' tickets ('+(t>0?((c.parsed/t)*100).toFixed(1):'0.0')+'%)'; 
                            } 
                        } 
                    } 
                } 
            } 
        });

        // Hide items that were excluded in the backend
        const chart = Chart.getChart(donutEl);
        if (data.excludedTypes && data.excludedTypes.length > 0) {
            data.requestTypes.keys.forEach((key, index) => {
                if (data.excludedTypes.includes(key)) {
                    chart.toggleDataVisibility(index);
                }
            });
            chart.update();
        }
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
                    legend: { 
                        display: true, 
                        position: 'top', 
                        labels: { 
                            font: { size: 10, family: ff }, 
                            boxWidth: 12, 
                            padding: 8, 
                            color: '#9ca3af',
                            generateLabels: function(chart) {
                                const data = chart.data;
                                return data.datasets.map(function(dataset, i) {
                                    const isHidden = !chart.isDatasetVisible(i);
                                    return {
                                        text: dataset.label,
                                        fillStyle: isHidden ? '#374151' : dataset.backgroundColor,
                                        strokeStyle: isHidden ? '#1f2937' : (dataset.borderColor || '#fff'),
                                        lineWidth: 1,
                                        hidden: isHidden,
                                        fontColor: isHidden ? '#4b5563' : '#9ca3af',
                                        datasetIndex: i
                                    };
                                });
                            }
                        } 
                    }, 
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


