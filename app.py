from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)

# Load pre-trained model
model = joblib.load('models/heart_model.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    features = [data['age'], data['sex'], data['cp'], data['trestbps'], data['chol']]
    
    prediction = model.predict([features])[0]
    return jsonify({"prediction": int(prediction)})

if __name__ == '__main__':
    app.run(debug=True)
