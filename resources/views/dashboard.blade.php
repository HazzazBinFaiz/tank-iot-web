<x-app-layout>
    <x-slot name="head">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f7fafc;
            }
            .chart-container {
                width: 100%;
                height: 300px;
            }
            .big-number-card {
                background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
                color: white;
            }
            .shimmer {
                background: #f3f4f6;
                background-image: linear-gradient(to right, #f3f4f6 0%, #e5e7eb 20%, #f3f4f6 40%, #f3f4f6 100%);
                background-repeat: no-repeat;
                background-size: 800px 104px;
                animation: shimmer 1s linear infinite;
            }
            @keyframes shimmer {
                0% {
                    background-position: -800px 0;
                }
                100% {
                    background-position: 800px 0;
                }
            }
        </style>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="w-full p-2">
        <div class="w-full p-2 bg-white">
            API Key : {{ \App\Lib\Hexer::encode(auth()->id()) }}
        </div>
    </div>

    <div class="w-full flex flex-wrap">
        <div class="w-full bg-white shadow-lg rounded-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Time Range</h3>
            <div class="flex flex-wrap gap-2">
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors active:bg-indigo-600 active:text-white" data-range="15m">15 Minutes</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="30m">30 Minutes</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="1h">1 Hour</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="6h">6 Hours</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="1d">1 Day</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="2d">2 Days</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="1w">1 Week</button>
                <button class="time-filter-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-full transition-colors" data-range="1m">1 Month</button>
            </div>
        </div>

        <div id="loadingIndicator" class="text-center py-12 text-gray-500 hidden">
            <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2">Loading data...</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Big Number Card for Tank Percentage -->
            <div class="col-span-1 md:col-span-3 flex justify-center">
                <div id="percentCard" class="big-number-card flex flex-col items-center justify-center p-8 shadow-2xl rounded-3xl w-full max-w-md h-64 transition-transform transform hover:scale-105">
                    <h3 class="text-xl font-medium mb-4 text-gray-200">Tank Percentage</h3>
                    <p id="percentValue" class="text-7xl font-extrabold loading-text">--%</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Air Temperature ($^\circ$C)</h3>
                <div class="chart-container">
                    <canvas id="airTempChart"></canvas>
                </div>
            </div>
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Water Temperature ($^\circ$C)</h3>
                <div class="chart-container">
                    <canvas id="waterTempChart"></canvas>
                </div>
            </div>
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Humidity (%)</h3>
                <div class="chart-container">
                    <canvas id="airHumChart"></canvas>
                </div>
            </div>
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800">TDS (PPM)</h3>
                <div class="chart-container">
                    <canvas id="tdsChart"></canvas>
                </div>
            </div>
            <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Distance (cm)</h3>
                <div class="chart-container">
                    <canvas id="distanceCmChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const API_KEY = "e4da35318d5";
            // Replace with your actual API endpoint
            const API_ENDPOINT = "/api/sensor-data";

            let charts = {};
            let currentRange = '15m';
            let pollingInterval;
            let loadingTimeout;

            // DOM elements
            const loadingIndicator = document.getElementById('loadingIndicator');
            const percentValueElement = document.getElementById('percentValue');
            const timeButtons = document.querySelectorAll('.time-filter-btn');

            // Show a loading state
            function showLoading() {
                loadingIndicator.classList.remove('hidden');
                percentValueElement.classList.add('shimmer');
                percentValueElement.textContent = '--%';
            }

            // Hide the loading state
            function hideLoading() {
                loadingIndicator.classList.add('hidden');
                percentValueElement.classList.remove('shimmer');
            }

            // Fetch data from the API
            async function fetchData(range) {
                clearTimeout(loadingTimeout);
                showLoading();

                // Fake delay to show loading indicator
                loadingTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`${API_ENDPOINT}?range=${range}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-API-Key': API_KEY
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data && data.records) {
                            processDataAndRender(data.records);
                        } else {
                            console.error("Invalid data format from API:", data);
                        }
                    } catch (error) {
                        console.error("Could not fetch data:", error);
                        // Display a user-friendly error
                        percentValueElement.textContent = 'Error';
                    } finally {
                        hideLoading();
                    }
                }, 500); // Wait 500ms before fetching
            }

            // Process data and update UI
            function processDataAndRender(records) {
                if (records.length === 0) {
                    percentValueElement.textContent = 'N/A';
                    updateCharts([], true);
                    return;
                }

                // Sort records by timestamp to ensure charts are correct
                records.sort((a, b) => a.timestamp - b.timestamp);

                // Update big number card with the latest percentage
                const latestRecord = records[records.length - 1];
                percentValueElement.textContent = `${(latestRecord.percent || 0).toFixed(1)}%`;

                // Prepare data for Chart.js
                const labels = records.map(record => {
                    const date = new Date(record.timestamp * 1000);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                });
                const datasets = {
                    airTemp: records.map(record => record.airTemp),
                    waterTemp: records.map(record => record.waterTemp),
                    airHum: records.map(record => record.airHum),
                    tds: records.map(record => record.tds),
                    distanceCm: records.map(record => record.distance_cm),
                };

                updateCharts(labels, datasets);
            }

            // Update all charts
            function updateCharts(labels, datasets) {
                Object.keys(datasets).forEach(sensorName => {
                    if (charts[sensorName]) {
                        charts[sensorName].data.labels = labels;
                        charts[sensorName].data.datasets[0].data = datasets[sensorName];
                        charts[sensorName].update();
                    } else {
                        const ctx = document.getElementById(`${sensorName}Chart`).getContext('2d');
                        charts[sensorName] = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: getChartLabel(sensorName),
                                    data: datasets[sensorName],
                                    borderColor: getChartColor(sensorName),
                                    backgroundColor: getChartColor(sensorName, 0.2),
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: false },
                                    x: {
                                        grid: { display: false },
                                        ticks: { maxRotation: 0, minRotation: 0 }
                                    }
                                },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }
                });
            }

            // Helper to get chart label
            function getChartLabel(name) {
                const labels = {
                    airTemp: 'Air Temperature',
                    waterTemp: 'Water Temperature',
                    airHum: 'Humidity',
                    tds: 'TDS',
                    distanceCm: 'Distance (cm)'
                };
                return labels[name] || name;
            }

            // Helper to get chart colors
            function getChartColor(name, opacity = 1) {
                const colors = {
                    airTemp: `rgba(255, 99, 132, ${opacity})`,
                    waterTemp: `rgba(54, 162, 235, ${opacity})`,
                    airHum: `rgba(75, 192, 192, ${opacity})`,
                    tds: `rgba(153, 102, 255, ${opacity})`,
                    distanceCm: `rgba(255, 159, 64, ${opacity})`
                };
                return colors[name] || `rgba(0, 0, 0, ${opacity})`;
            }

            // Handle time filter button clicks
            timeButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    // Remove active class from all buttons
                    timeButtons.forEach(btn => btn.classList.remove('active:bg-indigo-600', 'active:text-white'));
                    // Add active class to the clicked button
                    e.target.classList.add('active:bg-indigo-600', 'active:text-white');

                    currentRange = e.target.dataset.range;
                    clearInterval(pollingInterval);
                    fetchData(currentRange);
                    startPolling();
                });
            });

            // Start polling for new data
            function startPolling() {
                pollingInterval = setInterval(() => {
                    fetchData(currentRange);
                }, 1000); // Poll every 30 seconds
            }

            // Initial data fetch and polling start
            fetchData(currentRange);
            startPolling();
        });
    </script>
</x-app-layout>
