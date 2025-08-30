
from flask import Flask, jsonify
from flask_cors import CORS, cross_origin

app = Flask(__name__)
CORS(app)

import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BCM)

# Define the GPIO pin you connected the remote to (GPIO23)
REMOTE_PIN = 23

GPIO.setup(REMOTE_PIN, GPIO.OUT, initial=GPIO.HIGH)

def press_button():
    try:
        GPIO.output(REMOTE_PIN, GPIO.LOW)
        time.sleep(2)  # Keep the "press" for 2 seconds
        GPIO.output(REMOTE_PIN, GPIO.HIGH)
        print("Button pressed!")
    except Exception as e:
        print(f"An error occurred: {e}")

@app.route('/press', methods=['POST', 'GET'])
@cross_origin()
def handle_press():
    press_button()
    return jsonify({"message": "Button was pressed!"}), 200

if __name__ == "__main__":
    print("Flask server running. Waiting for request...")
    try:
        app.run(host="0.0.0.0", port=5000)
    finally:
        GPIO.cleanup()
