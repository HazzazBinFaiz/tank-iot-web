<x-app-layout>
    <div class="w-full flex flex-wrap space-y-2">
        <!-- Warning Message -->
        <div id="offlineWarning" class="hidden bg-red-500 text-white p-2 rounded text-center w-full"></div>

        <!-- Range Filter -->
        <div class="w-full flex flex-wrap gap-4 items-center space-x-2 mb-2">
            <select id="rangeSelector" class="border rounded p-2">
                <option value="15m">Last 15 min</option>
                <option value="30m">Last 30 min</option>
                <option value="1h">Last 1 hour</option>
                <option value="2h">Last 2 hours</option>
                <option value="1d" selected>Last 1 day</option>
                <option value="1w">Last 1 week</option>
                <option value="1m">Last 1 month</option>
                <option value="custom">Custom</option>
            </select>
            <input type="datetime-local" id="startDate" class="border rounded p-2 hidden">
            <input type="datetime-local" id="endDate" class="border rounded p-2 hidden">
            <button id="applyCustomRange" class="border bg-blue-500 text-white px-3 py-1 rounded hidden">Apply</button>
        </div>

        <!-- Tank View -->
        <div class="w-full md:w-1/2 lg:w-1/3 p-2 h-full">
            <div class="bg-white rounded p-4 flex justify-center relative h-full">
                <div class="water-tank bg-slate-200/50 rounded-full">
                    <div class="liquid-container" id="liquidContainer">
                        <x-liquid-tank/>
                    </div>
                </div>
                <div id="tankValue" class="text-5xl text-black absolute h-full w-full flex justify-center items-center z-10">0 %</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="w-full md:w-1/2 lg:w-2/3 p-2 h-full">
            <div class="p-4 bg-white w-full h-full">
                <canvas id="waterLevelChart"></canvas>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-2 h-full">
            <div class="p-4 bg-white w-full h-full">
                <canvas id="tankTempChart"></canvas>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-2 h-full">
            <div class="p-4 bg-white w-full h-full">
                <canvas id="tankHumidityChart"></canvas>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-2 h-full">
            <div class="p-4 bg-white w-full h-full">
                <canvas id="waterTempChart"></canvas>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-2 h-full">
            <div class="p-4 bg-white w-full h-full">
                <canvas id="waterTdsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const liquidContainer = document.getElementById("liquidContainer");
        const tankValueContainer = document.getElementById("tankValue");
        const offlineWarning = document.getElementById("offlineWarning");

        function updateTank(value) {
            const percent = parseFloat(value);
            liquidContainer.style.setProperty("--level", 100 - percent);
            tankValueContainer.innerText = percent + ' %';
        }

        // Chart Setup
        function createChart(ctx, label) {
            return new Chart(ctx, {
                type: 'line',
                data: { labels: [], datasets: [{ label: label, data: [], borderWidth: 2 }] },
                options: { responsive: true, maintainAspectRatio: true }
            });
        }

        function resetChart(chart) {
            chart.data.labels = [];
            chart.data.datasets[0].data = [];
            chart.update();
        }

        const waterLevelChart = createChart(document.getElementById("waterLevelChart"), "Water Level (%)");
        const tankTempChart = createChart(document.getElementById("tankTempChart"), "Tank Temp (°C)");
        const tankHumidityChart = createChart(document.getElementById("tankHumidityChart"), "Humidity (%)");
        const waterTempChart = createChart(document.getElementById("waterTempChart"), "Water Temp (°C)");
        const waterTdsChart = createChart(document.getElementById("waterTdsChart"), "TDS (ppm)");

        function addPoint(chart, label, value) {
            chart.data.labels.push(label);
            chart.data.datasets[0].data.push(value);
            chart.update();
        }

        // State
        let currentRange = '1d';
        let customStart = null;
        let customEnd = null;
        let lastDataId = null;
        let lastDataTime = null;
        let seenIDS = [];

        // Data Fetch Logic
        async function fetchReadings() {
            try {
                let apiUrl = `${location.pathname}`;
                const params = new URLSearchParams();

                if (currentRange === "custom" && customStart && customEnd) {
                    params.append("start_date", customStart.replace("T", " ") + ":00");
                    params.append("end_date", customEnd.replace("T", " ") + ":00");
                } else {
                    params.append("range", currentRange);
                }

                if (lastDataId) {
                    params.append("after_id", lastDataId);
                }

                apiUrl += `?${params.toString()}`;
                const response = await axios.get(apiUrl);
                const readings = response.data;

                if (readings.length > 0) {
                    readings.forEach(r => {
                        if (!seenIDS.includes(r.id)) {
                            lastDataId = r.id; // keep track of last ID
                            lastDataTime = new Date(r.created_at);
                            updateTank(r.water_percent);

                            const label = new Date(r.created_at).toLocaleTimeString();
                            addPoint(waterLevelChart, label, r.water_percent);
                            addPoint(tankTempChart, label, r.air_temp);
                            addPoint(tankHumidityChart, label, r.air_humidity);
                            addPoint(waterTempChart, label, r.water_temp);
                            addPoint(waterTdsChart, label, r.tds);
                            seenIDS.push(r.id);
                        }
                    });
                }

                checkDeviceStatus();
            } catch (e) {
                console.error(e);
            } finally {
                setTimeout(fetchReadings, 1000);
            }
        }

        // Device Status
        function formatTimeDiff(ms) {
            let seconds = Math.floor(ms / 1000);
            let minutes = Math.floor(seconds / 60);
            let hours = Math.floor(minutes / 60);

            seconds %= 60;
            minutes %= 60;

            if (hours > 0) return `${hours}h ${minutes}m ${seconds}s`;
            if (minutes > 0) return `${minutes}m ${seconds}s`;
            return `${seconds}s`;
        }

        function checkDeviceStatus() {
            if (lastDataTime) {
                const diffMs = Date.now() - lastDataTime.getTime();
                if (diffMs > 60000) {
                    offlineWarning.innerText = `Device may be offline. Last data was ${formatTimeDiff(diffMs)} ago`;
                    offlineWarning.classList.remove("hidden");
                } else {
                    offlineWarning.classList.add("hidden");
                }
            }
        }

        // Range Change Handling
        function clearAllCharts() {
            resetChart(waterLevelChart);
            resetChart(tankTempChart);
            resetChart(tankHumidityChart);
            resetChart(waterTempChart);
            resetChart(waterTdsChart);
            seenIDS = [];
            updateTank(0)
        }

        document.getElementById("rangeSelector").addEventListener("change", function () {
            currentRange = this.value;
            lastDataId = null; // reset ID so we get full fresh data
            clearAllCharts();

            if (currentRange === "custom") {
                document.getElementById("startDate").classList.remove("hidden");
                document.getElementById("endDate").classList.remove("hidden");
                document.getElementById("applyCustomRange").classList.remove("hidden");
            } else {
                document.getElementById("startDate").classList.add("hidden");
                document.getElementById("endDate").classList.add("hidden");
                document.getElementById("applyCustomRange").classList.add("hidden");
                fetchReadings();
            }
        });

        document.getElementById("applyCustomRange").addEventListener("click", function () {
            customStart = document.getElementById("startDate").value;
            customEnd = document.getElementById("endDate").value;
            lastDataId = null;
            clearAllCharts();
            fetchReadings();
        });

        fetchReadings();
    </script>

</x-app-layout>
