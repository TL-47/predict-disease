from flask import Flask, request, jsonify
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
import joblib

app = Flask(__name__)

# Load pre-trained model
model = joblib.load('models/heart_model.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    features = [data['age'], data['sex'], data['chest_pain'], data['resting_bp'], data['cholesterol'],
                data['fasting_bs'], data['resting_ecg'], data['max_hr'], data['exercise_angina'], 
                data['oldpeak'], data['slope'], data['ca'], data['thal']]
    
    prediction = model.predict([features])[0]
    return jsonify({"prediction": int(prediction)})

if __name__ == '__main__':
    app.run(debug=True)
