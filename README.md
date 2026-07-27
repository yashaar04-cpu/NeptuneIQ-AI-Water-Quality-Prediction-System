# NeptuneIQ – AI-Powered Water Quality Prediction System

## Overview

NeptuneIQ is an AI-powered web application developed to analyze and predict water quality based on key water parameters. The system utilizes a Machine Learning model integrated with a Flask API to classify water quality risk levels and provide recommendations for users.

The platform enables users to input water quality measurements, receive instant predictions, and store analysis results for future reference through a prediction history feature.

---

## Features

### User Management

* User registration
* User login and logout
* Session-based authentication

### Water Quality Analysis

* Input water quality parameters
* Real-time AI-powered prediction
* Water risk classification
* Action recommendations and advice

### Prediction History

* Store prediction records in a MySQL database
* View previous analyses
* Track historical prediction results

### AI Integration

* Machine Learning model for water quality classification
* Flask API communication
* Automated risk assessment

---

## Water Quality Parameters

NeptuneIQ analyzes the following parameters:

* pH Level
* Turbidity (NTU)
* Dissolved Oxygen (mg/L)
* Temperature (°C)
* Nitrate (mg/L)
* Total Dissolved Solids (TDS)
* Coliform (CFU/100mL)

---

## Technology Stack

### Frontend

* HTML
* CSS
* JavaScript

### Backend

* PHP

### Database

* MySQL

### Artificial Intelligence

* Python
* Flask
* Scikit-learn
* Joblib

---

## System Architecture

1. User enters water quality parameters through the web interface.
2. PHP and JavaScript send the data to the Flask API.
3. The Machine Learning model processes the input data.
4. The model predicts the water quality risk level.
5. Flask returns the prediction result.
6. The website displays:

   * Risk Level
   * Recommendations
   * Required Actions
   * Flagged Parameters
7. Prediction records are stored in the MySQL database.
8. Users can review previous predictions through the Prediction History page.

---

## Installation Guide

### Prerequisites

* PHP 8.x
* Python 3.x
* MySQL
* Flask
* Scikit-learn
* Joblib

### Clone the Repository

```bash
git clone <repository-url>
```

### Configure Database

1. Create a database named:

```sql
CREATE DATABASE neptuneiq;
```

2. Import the provided SQL database file.

3. Update database credentials in:

```php
db.php
```

---

### Start the Flask API

Navigate to the AI model directory:

```bash
cd NeptuneIQ(AI model)
```

Run:

```bash
python app.py
```

The Flask server should start on:

```text
http://127.0.0.1:5000
```

---

### Start the PHP Server

Navigate to the NeptuneIQ web directory:

```bash
cd "NeptuneIQ html"
```

Run:

```bash
"C:\xampp\php\php.exe" -S localhost:8000
```

Open:

```text
http://localhost:8000
```

---

## Project Modules

### User Management Module

Handles user registration, login, logout, and authentication.

### Water Quality Analysis Module

Accepts water quality parameters and communicates with the AI model for predictions.

### AI Prediction Module

Processes data using a trained Machine Learning model and generates risk classifications.

### Prediction History Module

Stores and displays historical prediction records for users.

### Database Management Module

Manages user information and prediction data.

---

## Limitations

* Requires manual input of water quality parameters.
* Depends on the quality of the training dataset.
* Requires the Flask API server to be running.
* Limited to selected water quality parameters.
* Does not support IoT sensor integration.
* No advanced data visualization features.
* Designed primarily for educational purposes.

---

## Future Enhancements

* Real-time IoT sensor integration.
* Mobile application development.
* Advanced data visualization and dashboards.
* PDF report generation.
* Email and notification alerts.
* Additional water quality parameters.
* Improved AI model accuracy.
* Cloud deployment support.

---

## Author

**Yashaar Chang**

Diploma in Information Technology

Sunway College @ Velocity Malaysia

---

## License

This project was developed for academic and educational purposes.
