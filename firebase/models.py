from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()

class Patient(db.Model):
    __tablename__ = 'patient'
    pid = db.Column(db.Integer, primary_key=True)
    pemail = db.Column(db.String(255), nullable=True)
    pname = db.Column(db.String(255), nullable=True)
    ppassword = db.Column(db.String(255), nullable=True)
    paddress = db.Column(db.String(255), nullable=True)
    pnic = db.Column(db.String(15), nullable=True)
    pdob = db.Column(db.Date, nullable=True)
    ptel = db.Column(db.String(15), nullable=True)
    pdf_path = db.Column(db.String(255), nullable=True)
