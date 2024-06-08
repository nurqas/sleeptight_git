from flask import Flask, render_template
from flask_cors import CORS
from pdf_functions import pdf_blueprint
from models import db

app = Flask(__name__, template_folder='templates')
CORS(app)

# Database configuration (example)
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:@localhost/edoc'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.init_app(app)

# Register the PDF blueprint
app.register_blueprint(pdf_blueprint)


# from flask import jsonify, render_template
from flask import jsonify
import firebase_admin
from firebase_admin import credentials, db
import pickle
import pandas as pd

# Initialize Firebase
cred = credentials.Certificate("sleeptight-e07fb-firebase-adminsdk-az8uc-15af14e0d4.json")
firebase_admin.initialize_app(cred, {
    'databaseURL': 'https://sleeptight-e07fb-default-rtdb.asia-southeast1.firebasedatabase.app/'
})


# Load the trained model and label encoders
with open('model.pkl', 'rb') as f:
    model_data = pickle.load(f)
    model = model_data['model']
    label_encoders = model_data['label_encoders']

# Define feature names
FEATURE_NAMES = ['Person ID', 'Gender', 'Age', 'Occupation', 'Sleep Duration', 'Quality of Sleep',
                 'Physical Activity Level', 'Stress Level', 'BMI Category', 'Heart Rate',
                 'Daily Steps', 'BloodPressure_Upper_Value', 'BloodPressure_Lower_Value']

@app.route('/predict', methods=['GET'])
def predict():
    try:
        # Get data from Firebase
        ref = db.reference('Sheet1')
        data = ref.get()
        # Convert JSON to DataFrame
        person_df = pd.DataFrame(data)
        # Ensure the DataFrame has the correct columns
        person_df = person_df.reindex(columns=FEATURE_NAMES)

        # Encode categorical features
        for col in ['Gender', 'Occupation', 'BMI Category']:
            if col in person_df:
                person_df[col] = label_encoders[col].transform(person_df[col])

        # Drop unnecessary columns
        X = person_df.drop(['Person ID'], axis=1)

        # Make prediction
        prediction = model.predict(X)

        return jsonify({'prediction': prediction.tolist()})
    except Exception as e:
        print("Error:", e)
        return jsonify({'error': str(e)})


if __name__ == '__main__':
    app.run(debug=True)

