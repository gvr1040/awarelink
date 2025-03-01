from flask import Flask, request, render_template
from twilio.rest import Client
import requests
import os

app = Flask(__name__)

# Twilio credentials (set as environment variables in Replit)
TWILIO_SID = os.getenv("TWILIO_SID")
TWILIO_AUTH_TOKEN = os.getenv("TWILIO_AUTH_TOKEN")
TWILIO_PHONE = os.getenv("TWILIO_PHONE")
GOOGLE_MAPS_API_KEY = os.getenv("GOOGLE_MAPS_API_KEY")

client = Client(TWILIO_SID, TWILIO_AUTH_TOKEN)

def get_test_centers(location):
    url = f"https://maps.googleapis.com/maps/api/place/textsearch/json?query=STD+Testing+Centers+near+{location}&key={GOOGLE_MAPS_API_KEY}"
    response = requests.get(url)
    data = response.json()
    centers = [place["name"] + " - " + place["formatted_address"] for place in data.get("results", [])[:3]]
    return centers

def send_sms(phone_number, centers):
    message_body = "Nearby STD Test Centers:\n" + "\n".join(centers)
    client.messages.create(
        body=message_body,
        from_=TWILIO_PHONE,
        to=phone_number
    )

@app.route("/", methods=["GET", "POST"])
def index():
    if request.method == "POST":
        location = request.form.get("location")
        phone_numbers = request.form.get("phone_numbers").split(",")
        centers = get_test_centers(location)
        for number in phone_numbers:
            send_sms(number.strip(), centers)
        return "Messages Sent!"
    return render_template("index.html")

if __name__ == "__main__":
    app.run(debug=True)
