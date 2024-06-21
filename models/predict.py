from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)

# Load pre-trained models
heart_model = joblib.load('heart_model.pkl')
# Load other models similarly

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    age = data['age']
    bmi = data['bmi']
    insulin = data['insulin']
    # Get other variables as needed

    # Example prediction for heart disease
    prediction = heart_model.predict([[age, bmi, insulin]])[0]
    
    return jsonify({"prediction": prediction})

if __name__ == '__main__':
    app.run(debug=True)
