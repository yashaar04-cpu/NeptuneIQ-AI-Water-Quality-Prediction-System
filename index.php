<?php
session_start();
$username = $_SESSION['user'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Water Quality Dashboard</title>
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
		display: flex; 
		gap: 20px; 
		margin-top: 20px; 
		}
		
  .box { 
		background: white;
		padding: 20px; 
		border-radius: 8px;
		flex: 1;
		box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
		}
		
   .form-group { 
		display: flex; 
		justify-content: space-between;
		margin-bottom: 10px; 
		align-items: center; 
		}
		
    .form-group input {
			width: 120px; 
			padding: 6px;
			border: 1px solid #ccc;
			border-radius: 4px; 
	 }		
 .btn-submit { 
		width: 100%; 
		padding: 12px;
		background: #27ae60; 
		color: white; 
		border: none;
		border-radius: 4px; 
		cursor: pointer; 
		font-size: 1rem;
 }
    .result-badge {
			padding: 15px; 
			color: white;
			border-radius: 6px;
			text-align: center; 
			font-size: 1.4rem;
			font-weight: bold;
			margin-bottom: 15px;
		}		
   .warning-box {
			background: #fff3cd;
			border: 1px solid #ffeeba; 
			color: #856404; padding: 10px;
			border-radius: 4px; 
			margin-top: 15px; 
    }
.intro-section {
    display: flex;
    align-items: center;
    gap: 40px;
    background: white;
    padding: 30px;
    margin-top: 20px;
    border-radius: 10px;
}
.intro-image {
    width: 50%;
}
.intro-image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
}
.intro-info {
    width: 50%;
}
.intro-info h1 {
    color: #2c3e50;
    font-size: 32px;
}

.intro-info p {
    line-height: 1.6;
    color: #555;
}
.intro-info li {
    margin-bottom: 8px;
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
        Welcome, <strong><?= htmlspecialchars($username) ?></strong>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="predictions.php">Predictions</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

</div>
</div>

<div class="intro-section">
<div class="intro-image">
    <img id="waterImage" src="Images/image(1).jpg">
</div>

    <div class="intro-info">
        <h1>🫧Welcome to NeptuneIQ🫧</h1>
        <p>
            NeptuneIQ is an AI-powered water quality analysis system
            that predicts water safety levels using machine learning.
        </p>
        <p>
            By analysing parameters such as pH, turbidity,
            dissolved oxygen, nitrate, TDS and coliform levels,
            NeptuneIQ provides fast water quality predictions.
        </p>
    </div>
</div>

<div class="container">
    <div class="box">
        <h3>INPUT SAMPLE METRICS</h3>
        <form id="waterForm">
            <div class="form-group"><label>pH Level (6.5 - 8.5):</label><input type="number" step="0.1" id="ph" value="7.2" required></div>
            <div class="form-group"><label>Turbidity (NTU):</label><input type="number" step="0.1" id="turbidity" value="1.5" required></div>
            <div class="form-group"><label>Dissolved Oxygen (mg/L):</label><input type="number" step="0.1" id="dissolved_oxygen" value="8.0" required></div>
            <div class="form-group"><label>Temperature (°C):</label><input type="number" step="0.1" id="temperature" value="22.0" required></div>
            <div class="form-group"><label>Nitrate (mg/L):</label><input type="number" step="0.1" id="nitrate" value="5.0" required></div>
            <div class="form-group"><label>TDS (mg/L):</label><input type="number" step="0.1" id="tds" value="200.0" required></div>
            <div class="form-group"><label>Coliform (CFU/100mL):</label><input type="number" step="0.1" id="coliform" value="0.5" required></div>
            <button type="submit" class="btn-submit">Analyze with NeptuneIQ</button>
        </form>
    </div>

    <div class="box">
        <h3>NeptuneIQ Prediction Results</h3>
        <div id="output">
            <p style="color: #7f8c8d;">Enter metrics on the left and click "Analyze with NeptuneIQ".</p>
        </div>
    </div>
</div>

<script>
document.getElementById('waterForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const outputDiv = document.getElementById('output');
    outputDiv.innerHTML = '<p>Querying AI Model...</p>';

    const payload = {
        ph: parseFloat(document.getElementById('ph').value),
        turbidity: parseFloat(document.getElementById('turbidity').value),
        dissolved_oxygen: parseFloat(document.getElementById('dissolved_oxygen').value),
        temperature: parseFloat(document.getElementById('temperature').value),
        nitrate: parseFloat(document.getElementById('nitrate').value),
        tds: parseFloat(document.getElementById('tds').value),
        coliform: parseFloat(document.getElementById('coliform').value)
    };

    try {
        const response = await fetch('http://127.0.0.1:5000/predict', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

if (data.success) {

    await fetch("save_predictions.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            ph: payload.ph,
            turbidity: payload.turbidity,
            dissolved_oxygen: payload.dissolved_oxygen,
            temperature: payload.temperature,
            nitrate: payload.nitrate,
            tds: payload.tds,
            coliform: payload.coliform,
            risk_level: data.risk_level,
            advice: data.advice
        })
    });

    let flagsHTML = '';
            if (data.flagged_parameters.length > 0) {

                flagsHTML = `
                    <div class="warning-box">
                        <strong>⚠️Out-of-Range Parameters⚠️:</strong>
                        <ul>
                            ${data.flagged_parameters.map(f => 
                                `<li>${f.parameter}: ${f.value} ${f.unit} (${f.status})</li>`
                            ).join('')}
                        </ul>
                    </div>`;
            }

            outputDiv.innerHTML = `
                <div class="result-badge" style="background-color: ${data.color}">
                    ${data.risk_level}
                </div>

                <p><strong>Advice:</strong> ${data.advice}</p>

                <p><strong>Action Required:</strong> ${data.action}</p>

                ${flagsHTML}
            `;

        } else {

            outputDiv.innerHTML = `
            <p style="color:red;">
            Error: ${data.error}
            </p>`;

        }

    } catch (err) {

        outputDiv.innerHTML = `
        <p style="color:red;">
        Failed to connect to Flask API. Make sure Flask is running on port 5000.
        </p>`;

    }

});
</script>
<script>
let images = [
    "Images/image(1).jpg",
    "Images/image(2).jpg",
    "Images/image(3).jpg",
    "Images/image(4).jpg"
];

let current = 0;
setInterval(function(){
    current++;
    if(current >= images.length){
        current = 0;
    }
    document.getElementById("waterImage").src = images[current];
}, 3000);
</script>

</body>
</html>