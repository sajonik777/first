from flask import Flask, jsonify, render_template, request, flash
from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField
from wtforms.validators import DataRequired
import subprocess
import json

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key'  # Replace with your secret key

class IPForm(FlaskForm):
    ip_addresses = StringField('IP Addresses', validators=[DataRequired()], render_kw={"placeholder": "Enter IP addresses separated by commas"})
    naviseccli_path = StringField('Naviseccli Path', validators=[DataRequired()], render_kw={"placeholder": "Enter path to Naviseccli"})
    submit = SubmitField('Submit')

@app.route('/', methods=['GET', 'POST'])
def index():
    form = IPForm()
    if form.validate_on_submit():
        ips = form.ip_addresses.data.split(',')
        naviseccli_path = form.naviseccli_path.data
        flash('Form submitted successfully!', 'success')
        return run_script(ips, naviseccli_path)
    return render_template('index.html', form=form)

@app.route('/run_script', methods=['POST'])
def run_script(ips, naviseccli_path):
    commands = [
        "getagent",
        "getsystem",
        "getdisk",
        "getlun",
        "getstoragegroup",
        "getpool",
        "getport",
        "getsp",
        "getcache",
        "getlog",
        "getarray",
        "getmirrorview",
        "getsnapsure",
        "getsnapview",
        "getsanlun",
        "getnaspool",
        "getnasvolume",
        "getnasvdm",
        "getnasinterface",
        "getnascheckpoint"
    ]
    data = {}
    for ip in ips:
        data[ip] = {}
        for command in commands:
            result = subprocess.run([naviseccli_path, "-h", ip, command], capture_output=True, text=True)
            output = result.stdout
            lines = output.split("\n")
            for line in lines:
                fields = line.split(":")
                if len(fields) == 2:
                    key = fields[0].strip()
                    value = fields[1].strip()
                    data[ip][key] = value
    return jsonify(data)

if __name__ == '__main__':
    app.run(debug=True)