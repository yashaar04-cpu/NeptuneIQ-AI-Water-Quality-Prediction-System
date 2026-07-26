<?php
session_start();
$username = $_SESSION['user'] ?? 'Guest';
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "neptuneiq"
);

if ($conn->connect_error) {
    die("Database connection has failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM predictions ORDER BY prediction_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
body {
    font-family: Arial, sans-serif;
    background:#1B5583;
    margin: 0;
    padding: 20px;
}

.navbar {
		background:black; 
		color: white; 
		padding: 15px 20px; 
		display: flex; 
		justify-content: 
		space-between; 
		align-items: center; 
		border-radius: 6px; 
}
.navbar a { 
    background: #003366;
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    margin-left: 10px;
    border-radius: 6px;
    display: inline-block;
    transition: 0.3s;
}

.navbar a:hover {
    background: #00509e;  
}

.container {
    background: white;
    margin-top: 20px;
    padding: 20px;
    border-radius: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #2c3e50;
    color: white;
    padding: 12px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

tr:hover {
    background: #f2f2f2;
}

.safe {
    color: green;
    font-weight: bold;
}

.moderate {
    color: orange;
    font-weight: bold;
}

.high {
    color: red;
    font-weight: bold;
}

.dangerous {
    color: purple;
    font-weight: bold;
}

</style>
</head>
<body>

<div class="navbar">
    <div style="display:flex; align-items:center; gap:10px;">
        <img src="Images/logo.png" alt="NeptuneIQ Logo" width="100">
        <h2 style="margin:0;">NeptuneIQ</h2>
    </div>
<div>
Welcome, 
<strong>
<?= htmlspecialchars($username) ?>
</strong>
<a href="index.php">Analyze</a>
<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">
<h2>Water Quality Prediction History</h2>

<table>

<tr>
<th>Date</th>
<th>pH</th>
<th>Turbidity</th>
<th>Dissolved Oxygen</th>
<th>Temperature</th>
<th>Nitrate</th>
<th>TDS</th>
<th>Coliform</th>
<th>Risk Level</th>
<th>Advice</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $risk = strtolower($row['risk_level']);
        echo "<tr>";
        echo "<td>".$row['prediction_date']."</td>";
        echo "<td>".$row['ph']."</td>";
        echo "<td>".$row['turbidity']."</td>";
        echo "<td>".$row['dissolved_oxygen']."</td>";
        echo "<td>".$row['temperature']."</td>";
        echo "<td>".$row['nitrate']."</td>";
        echo "<td>".$row['tds']."</td>";
        echo "<td>".$row['coliform']."</td>";
        echo "<td class='$risk'>";
        echo $row['risk_level'];
        echo "</td>";
        echo "<td>".$row['advice']."</td>";
        echo "</tr>";

    }

} else {
    echo "
    <tr>
    <td colspan='10'>
    No predictions found yet.
    </td>
    </tr>
    ";
}

?>
</table>
</div>

</body>
</html>