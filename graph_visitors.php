<?php
include "db.php"; // Including the database connection

// Fetch data for Domestic Visitors from the database
$query = "SELECT * FROM domestic_visitors";
$result = mysqli_query($conn, $query);

// Prepare data for the chart
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['year']][] = [
        'component' => $row['component'],
        'amount' => (int)$row['amount']
    ];
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        #chart-container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
        }

        #barChart {
            width: 100%; 
        }

        #pieChart {
            width: 10%; /* Reduced width significantly */
            margin: 10px auto; 
        }

        /* Add space between bar chart and pie chart */
        #pieChartContainer {
            margin-top: 20px; /* Adjust as needed */ 
        }
    </style>
    <title>Graphs for Domestic Visitors</title>
</head>
<body>
    <div id="chart-container">
        <h1>Graphs for Domestic Visitors (2010 and 2011)</h1>

        <?php foreach ($data as $year => $items): ?>
            <h2>Year: <?php echo $year; ?></h2>

            <canvas id="barChart<?php echo $year; ?>"></canvas>

            <div id="pieChartContainer"> 
                <canvas id="pieChart<?php echo $year; ?>"></canvas>
            </div>

            <script>
                const components<?php echo $year; ?> = <?php echo json_encode(array_column($items, 'component')); ?>;
                const amounts<?php echo $year; ?> = <?php echo json_encode(array_column($items, 'amount')); ?>;

                // Bar Chart
                const barCtx<?php echo $year; ?> = document.getElementById('barChart<?php echo $year; ?>').getContext('2d');
                new Chart(barCtx<?php echo $year; ?>, {
                    type: 'bar',
                    data: {
                        labels: components<?php echo $year; ?>,
                        datasets: [{
                            label: 'Expenditure (RM million)',
                            data: amounts<?php echo $year; ?>,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#C9CBFF'],
                        }]
                    },
                    options: {
                        responsive: true, 
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip: false, 
                                    maxRotation: 45, 
                                    minRotation: 0 
                                }
                            }
                        }
                    }
                });

                // Pie Chart
                const pieCtx<?php echo $year; ?> = document.getElementById('pieChart<?php echo $year; ?>').getContext('2d');
                new Chart(pieCtx<?php echo $year; ?>, {
                    type: 'pie',
                    data: {
                        labels: components<?php echo $year; ?>,
                        datasets: [{
                            data: amounts<?php echo $year; ?>,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#C9CBFF'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        }
                    }
                });
            </script>
        <?php endforeach; ?>
    </div>
</body>
</html>
