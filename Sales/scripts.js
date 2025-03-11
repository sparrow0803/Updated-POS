document.addEventListener('DOMContentLoaded', function () {
    const lineChartContainer = document.getElementById('lineChart').parentNode;
    const barChartContainer = document.getElementById('barChart').parentNode;
    const graphTypeSelect = document.getElementById('graphType');
    const dateRangeSelect = document.getElementById('daterange');

    let lineChart, barChart; // Declare chart variables globally

    // Function to fetch data and update charts
    function fetchData() {
        const selectedGraph = graphTypeSelect.value;
        const selectedDateRange = dateRangeSelect.value;

        
        fetch('data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'dateRange': selectedDateRange,
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Destroy existing charts if they exist
            if (lineChart) lineChart.destroy();
            if (barChart) barChart.destroy();

            // Update the correct chart based on the selected graph type
            if (selectedGraph === 'line') {
                updateLineChart(data.lineChartData);
                lineChartContainer.style.display = 'block';
                barChartContainer.style.display = 'none';
            } else if (selectedGraph === 'bar') {
                updateBarChart(data.barChartData);
                lineChartContainer.style.display = 'none';
                barChartContainer.style.display = 'block';
            }
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // Function to update line chart with fetched data
    function updateLineChart(lineChartData) {
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        lineChart = new Chart(lineCtx, { // Assign the new chart instance to lineChart
            type: 'line',
            data: {
                labels: lineChartData.map(entry => entry.date),
                datasets: [{
                    label: 'Total Sales',
                    data: lineChartData.map(entry => entry.totalamount),
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    }

    // Function to update bar chart with fetched data
    function updateBarChart(barChartData) {
        const barCtx = document.getElementById('barChart').getContext('2d');
        barChart = new Chart(barCtx, { // Assign the new chart instance to barChart
            type: 'bar',
            data: {
                labels: barChartData.map(entry => entry.date),
                datasets: [{
                    label: 'Total Sales',
                    data: barChartData.map(entry => entry.totalamount),
                    backgroundColor: 'rgba(242, 255, 71, 0.67)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Fetch initial data and set up the chart
    fetchData();

    // Event listeners for dropdown changes
    graphTypeSelect.addEventListener('change', fetchData);
    dateRangeSelect.addEventListener('change', fetchData);
});

document.addEventListener('DOMContentLoaded', function () {
    const exportCSVBtn = document.getElementById('exportCSV');

    exportCSVBtn.addEventListener('click', function () {
        // Get the datasets from the charts
        const lineChart = Chart.getChart('lineChart');
        const barChart = Chart.getChart('barChart');
        const lineChartData = lineChart.data;
        const barChartData = barChart.data;

        // Function to convert chart data to CSV format
        function convertToCSV(chartData) {
            const labels = chartData.labels;
            const datasets = chartData.datasets;

            let csvContent = "Label," + datasets.map(dataset => dataset.label).join(",") + "\n";

            labels.forEach((label, index) => {
                const values = datasets.map(dataset => dataset.data[index]);
                csvContent += `${label},${values.join(",")}\n`;
            });

            return csvContent;
        }

        // Convert chart data to CSV
        const lineChartCSV = convertToCSV(lineChartData);
        const barChartCSV = convertToCSV(barChartData);

        // Combine both line and bar chart CSV data
        const combinedCSV = lineChartCSV + '\n\n' + barChartCSV;

        // Create download link for the combined CSV file
        const combinedBlob = new Blob([combinedCSV], { type: 'text/csv;charset=utf-8' });
        const combinedURL = URL.createObjectURL(combinedBlob);

        // Create download link and trigger download
        const combinedLink = document.createElement('a');
        combinedLink.href = combinedURL;
        combinedLink.download = 'combinedCharts.csv';
        document.body.appendChild(combinedLink);
        combinedLink.click();

        // Cleanup
        document.body.removeChild(combinedLink);
    });
});


//-----Image Preview------//

function showPreview(id) {
    document.getElementById(id).style.display = "block";
}

function closePreview() {
    var overlays = document.querySelectorAll(".image-preview");
    overlays.forEach(function(overlay) {
        overlay.style.display = "none";
    });
}
