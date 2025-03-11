<?php
// Assuming you have a MySQL database
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "pos"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dateRange = $_POST['dateRange'] ?? 'day'; 
    $today = date('Y-m-d');

    if ($dateRange == 'day') {
        $sql = "SELECT * FROM invoice WHERE date = :today ORDER BY time";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $chartData = $fetchedData;
        
    } elseif ($dateRange == 'week') {
        $startDate = date('Y-m-d', strtotime('last monday'));
        $endDate = date('Y-m-d', strtotime('next sunday'));

        $sql = "SELECT date, totalamount FROM invoice WHERE date BETWEEN :start_date AND :end_date ORDER BY date";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $aggregatedData = [];
        foreach ($fetchedData as $row) {
            $date = $row['date'];
            $totalSale = $row['totalamount'];

            if (!isset($aggregatedData[$date])) {
                $aggregatedData[$date] = 0;
            }
            $aggregatedData[$date] += $totalSale;
        }

        $chartData = [];
        foreach ($aggregatedData as $date => $totalSale) {
            $chartData[] = ['date' => $date, 'totalamount' => $totalSale];
        }
        
    } elseif ($dateRange == 'month') {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');

        $sql = "SELECT date, totalamount FROM invoice WHERE date BETWEEN :start_date AND :end_date ORDER BY date";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $aggregatedData = [];
        foreach ($fetchedData as $row) {
            $date = $row['date'];
            $month = date('Y-m', strtotime($date));
            $totalSale = $row['totalamount'];

            if (!isset($aggregatedData[$month])) {
                $aggregatedData[$month] = 0;
            }
            $aggregatedData[$month] += $totalSale;
        }

        $chartData = [];
        foreach ($aggregatedData as $month => $totalSale) {
            $chartData[] = ['date' => $month, 'totalamount' => $totalSale];
        }
    }

    $response = [
        'lineChartData' => $chartData,
        'barChartData' => $chartData  
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
