# ============================================================
# WATER QUALITY ANALYZER - Jupyter Notebook Code
# Step 1: Build and Train the ML Model
# ============================================================
# Run each section as a cell in Jupyter Notebook

# -------------------------------------------------------
# CELL 1: Install required libraries
# -------------------------------------------------------
# Run this first in Jupyter:
# !pip install pandas numpy scikit-learn matplotlib seaborn joblib

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import classification_report, confusion_matrix
import joblib
import warnings
warnings.filterwarnings('ignore')

print("✅ Libraries loaded successfully!")


# -------------------------------------------------------
# CELL 2: Create Training Dataset
# (Simulated water quality data based on WHO standards)
# -------------------------------------------------------

np.random.seed(42)
n_samples = 1000

def generate_water_data(n):
    data = []
    for _ in range(n):
        # Randomly assign a risk level first, then generate parameters
        risk = np.random.choice(['Safe', 'Moderate', 'High Risk', 'Dangerous'],
                                 p=[0.4, 0.3, 0.2, 0.1])

        if risk == 'Safe':
            ph          = np.random.uniform(6.5, 8.5)
            turbidity   = np.random.uniform(0, 4)
            do_level    = np.random.uniform(6, 14)
            temperature = np.random.uniform(15, 25)
            nitrate     = np.random.uniform(0, 10)
            tds         = np.random.uniform(50, 300)
            coliform    = np.random.uniform(0, 1)

        elif risk == 'Moderate':
            ph          = np.random.uniform(6.0, 9.0)
            turbidity   = np.random.uniform(3, 10)
            do_level    = np.random.uniform(4, 7)
            temperature = np.random.uniform(20, 30)
            nitrate     = np.random.uniform(8, 25)
            tds         = np.random.uniform(250, 600)
            coliform    = np.random.uniform(1, 10)

        elif risk == 'High Risk':
            ph          = np.random.uniform(4.5, 6.0) if np.random.rand() > 0.5 else np.random.uniform(8.5, 10.0)
            turbidity   = np.random.uniform(8, 50)
            do_level    = np.random.uniform(2, 5)
            temperature = np.random.uniform(28, 38)
            nitrate     = np.random.uniform(20, 50)
            tds         = np.random.uniform(500, 900)
            coliform    = np.random.uniform(10, 100)

        else:  # Dangerous
            ph          = np.random.uniform(2.0, 4.5) if np.random.rand() > 0.5 else np.random.uniform(10.0, 14.0)
            turbidity   = np.random.uniform(50, 200)
            do_level    = np.random.uniform(0, 2)
            temperature = np.random.uniform(35, 50)
            nitrate     = np.random.uniform(50, 100)
            tds         = np.random.uniform(900, 2000)
            coliform    = np.random.uniform(100, 1000)

        data.append({
            'ph': round(ph, 2),
            'turbidity': round(turbidity, 2),
            'dissolved_oxygen': round(do_level, 2),
            'temperature': round(temperature, 2),
            'nitrate': round(nitrate, 2),
            'tds': round(tds, 2),
            'coliform': round(coliform, 2),
            'risk_level': risk
        })

    return pd.DataFrame(data)

df = generate_water_data(n_samples)
print("✅ Dataset created!")
print(f"Shape: {df.shape}")
print("\nRisk Level Distribution:")
print(df['risk_level'].value_counts())
print("\nSample Data:")
df.head()


# -------------------------------------------------------
# CELL 3: Exploratory Data Analysis (EDA)
# -------------------------------------------------------

fig, axes = plt.subplots(2, 4, figsize=(18, 8))
fig.suptitle('Water Quality Parameters by Risk Level', fontsize=14, fontweight='bold')

features = ['ph', 'turbidity', 'dissolved_oxygen', 'temperature', 'nitrate', 'tds', 'coliform']
colors = {'Safe': '#2ecc71', 'Moderate': '#f39c12', 'High Risk': '#e74c3c', 'Dangerous': '#8e44ad'}

for i, feature in enumerate(features):
    ax = axes[i // 4][i % 4]
    for risk, color in colors.items():
        subset = df[df['risk_level'] == risk][feature]
        ax.hist(subset, alpha=0.6, label=risk, color=color, bins=20)
    ax.set_title(feature.replace('_', ' ').title())
    ax.set_xlabel('Value')
    ax.legend(fontsize=6)

# Hide last empty subplot
axes[1][3].axis('off')
plt.tight_layout()
plt.savefig('water_quality_eda.png', dpi=150, bbox_inches='tight')
plt.show()
print("✅ EDA chart saved as water_quality_eda.png")


# -------------------------------------------------------
# CELL 4: Train the Model
# -------------------------------------------------------

# Features and target
X = df[features]
y = df['risk_level']

# Split data
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42, stratify=y
)

# Scale features
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled  = scaler.transform(X_test)

# Train Random Forest model
model = RandomForestClassifier(
    n_estimators=100,
    max_depth=10,
    random_state=42,
    class_weight='balanced'
)
model.fit(X_train_scaled, y_train)

# Evaluate
y_pred = model.predict(X_test_scaled)
print("✅ Model trained!\n")
print("=" * 50)
print("CLASSIFICATION REPORT")
print("=" * 50)
print(classification_report(y_test, y_pred))


# -------------------------------------------------------
# CELL 5: Confusion Matrix
# -------------------------------------------------------

cm = confusion_matrix(y_test, y_pred, labels=['Safe', 'Moderate', 'High Risk', 'Dangerous'])
plt.figure(figsize=(8, 6))
sns.heatmap(cm, annot=True, fmt='d', cmap='Blues',
            xticklabels=['Safe', 'Moderate', 'High Risk', 'Dangerous'],
            yticklabels=['Safe', 'Moderate', 'High Risk', 'Dangerous'])
plt.title('Confusion Matrix', fontsize=14, fontweight='bold')
plt.ylabel('Actual')
plt.xlabel('Predicted')
plt.tight_layout()
plt.savefig('confusion_matrix.png', dpi=150)
plt.show()


# -------------------------------------------------------
# CELL 6: Feature Importance
# -------------------------------------------------------

importances = model.feature_importances_
feat_df = pd.DataFrame({'Feature': features, 'Importance': importances})
feat_df = feat_df.sort_values('Importance', ascending=True)

plt.figure(figsize=(8, 5))
bars = plt.barh(feat_df['Feature'], feat_df['Importance'],
                color=['#3498db' if x > 0.1 else '#bdc3c7' for x in feat_df['Importance']])
plt.title('Feature Importance', fontsize=14, fontweight='bold')
plt.xlabel('Importance Score')
plt.tight_layout()
plt.savefig('feature_importance.png', dpi=150)
plt.show()
print("\nFeature Importances:")
print(feat_df.sort_values('Importance', ascending=False).to_string(index=False))


# -------------------------------------------------------
# CELL 7: Save Model & Scaler
# -------------------------------------------------------

joblib.dump(model, 'water_quality_model.pkl')
joblib.dump(scaler, 'water_quality_scaler.pkl')

print("✅ Model saved as: water_quality_model.pkl")
print("✅ Scaler saved as: water_quality_scaler.pkl")
print("\nThese 2 files are what Flask will use!")


# -------------------------------------------------------
# CELL 8: Test a Prediction (manual test)
# -------------------------------------------------------

def predict_water_quality(ph, turbidity, dissolved_oxygen,
                           temperature, nitrate, tds, coliform):
    """
    Predict water quality risk level from input parameters.
    Returns: risk level + confidence scores
    """
    input_data = np.array([[ph, turbidity, dissolved_oxygen,
                             temperature, nitrate, tds, coliform]])
    input_scaled = scaler.transform(input_data)

    prediction   = model.predict(input_scaled)[0]
    probabilities = model.predict_proba(input_scaled)[0]
    classes      = model.classes_

    prob_dict = {cls: round(float(prob) * 100, 1)
                 for cls, prob in zip(classes, probabilities)}

    return {
        'risk_level': prediction,
        'confidence': prob_dict
    }

# Test with a safe water sample
result = predict_water_quality(
    ph=7.2,
    turbidity=1.5,
    dissolved_oxygen=8.0,
    temperature=22.0,
    nitrate=5.0,
    tds=200.0,
    coliform=0.5
)
print("🧪 Test Prediction (Safe Water Sample):")
print(f"  Risk Level : {result['risk_level']}")
print(f"  Confidence : {result['confidence']}")

# Test with a dangerous water sample
result2 = predict_water_quality(
    ph=3.5,
    turbidity=120.0,
    dissolved_oxygen=0.8,
    temperature=42.0,
    nitrate=80.0,
    tds=1500.0,
    coliform=500.0
)
print("\n🧪 Test Prediction (Dangerous Water Sample):")
print(f"  Risk Level : {result2['risk_level']}")
print(f"  Confidence : {result2['confidence']}")
