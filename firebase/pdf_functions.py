from flask import Blueprint, request, jsonify, current_app
from models import db, Patient
import os
from datetime import datetime
from fpdf import FPDF
from flask_cors import CORS

pdf_blueprint = Blueprint('pdf', __name__)
CORS(pdf_blueprint)

@pdf_blueprint.route('/patient/save_pdf', methods=['POST', 'OPTIONS'])
def save_pdf():
    if request.method == 'OPTIONS':
        headers = {
            'Access-Control-Allow-Origin': 'http://localhost',
            'Access-Control-Allow-Methods': 'POST',
            'Access-Control-Allow-Headers': 'Content-Type'
        }
        return ('', 204, headers)

    try:
        data = request.json
        username = data['username']
        classification = data['classification']
        questions = data['questions']
        answers = data['answers']

        pdf_dir = os.path.join(current_app.root_path, 'pdfs')
        if not os.path.exists(pdf_dir):
            os.makedirs(pdf_dir)

        timestamp = datetime.now().strftime("%Y%m%d%H%M%S")
        pdf_filename = f'{username}_{timestamp}_survey_results.pdf'
        pdf_path = os.path.join(pdf_dir, pdf_filename)

        pdf = FPDF()
        pdf.add_page()
        pdf.set_font("Arial", size=12)
        pdf.cell(200, 10, txt=f"Username: {username}", ln=True)
        pdf.cell(200, 10, txt=f"Classification: {classification}", ln=True)
        for answer in answers:
            pdf.cell(200, 10, txt=answer, ln=True)
        pdf.output(pdf_path)

        patient = Patient.query.filter_by(pname=username).first()
        if patient:
            patient.pdf_path = pdf_path
            db.session.commit()
            response = jsonify({'pdfPath': pdf_path})
            return response, 200
        else:
            return jsonify({'error': 'Patient not found'}), 404
    except Exception as e:
        return jsonify({'error': str(e)}), 500
