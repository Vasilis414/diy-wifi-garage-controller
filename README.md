# Smart Garage Door with Raspberry Pi Zero

Turn your regular garage door into a smart garage door using a **microcontroller** like the Raspberry Pi Zero. This project allows you to control your garage remotely through a web interface or app.

---

## Features

* Turn a normal garage door opener into a smart device
* Control via web interface or mobile device
* Optional auto-start on boot
* Compatible with any microcontroller (code provided for Raspberry Pi Zero)

---

## Requirements

* **Garage door controller**
* **Soldering iron and solder**
* **Microcontroller** (Raspberry Pi Zero recommended for using provided code)
* **Wires / jumper cables**
* **Electrical tape**

---

## Step 1: Test Your Garage Controller

Before starting, verify that your garage controller can work with this project:

1. Open your garage door remote and remove the circuit board.
2. Locate the button that opens the garage.
3. Take a wire and touch both ends of the button contacts simultaneously.

   * If the controller LED lights up and your garage door opens, your controller is compatible.
<img width="176" height="235" alt="image" src="https://github.com/user-attachments/assets/dff150d3-e891-41c0-99dd-b428ebfe9281" />
<img width="209" height="236" alt="image" src="https://github.com/user-attachments/assets/489397ad-55ff-493e-a1fe-9573ac1281f8" />

---

## Step 2: Wiring the Controller

1. Solder **two wires** onto the button contacts on the remote board.
2. <img width="146" height="219" alt="image" src="https://github.com/user-attachments/assets/2e42d81c-f9e7-4623-8e72-d1a685aaba17" />
3. Connect **one wire to a GPIO pin** and the other to **GND** on your microcontroller (search online to find the pins).

> In this project, GPIO 23 was used. Change the GPIO pin in the code if you use a different one.

💡 **Tip:**

* Don’t directly insert wires into the holes. First, solider a piece of metal wire in the hole and then solider the cables on that for better connection.
* Insulate the wires with tape to prevent short circuits.

---

### Wiring Diagram

<img width="318" height="235" alt="image" src="https://github.com/user-attachments/assets/15822ef3-8227-430f-87ee-77f3a68a720f" />

```
Remote Button
[ o ] ------ Wire1 ------ GPIO23 (Pi Zero)
[ o ] ------ Wire2 ------ GND (Pi Zero)
```

---

## Step 3: Python Script

1. Install Python on your Raspberry Pi.
2. Copy the provided script `webbuttonpress.py` (or find an equivalent for your microcontroller).
3. Install dependencies:

```bash
pip install flask flask-cors
```

4. Update `REMOTE_PIN` in the script if you used a different GPIO.
5. Run the script:

```bash
python3 webbuttonpress.py
```

6. Open your browser at:

```
http://<your-device-ip>:5000/press
```

* The controller LED should light up if everything is connected correctly.

---

### Auto-Start Script

* **Option 1:** Add to `.bashrc` or `~/.profile`

```bash
python3 /path/to/webbuttonpress.py &
```

* **Option 2:** Use cron

```bash
crontab -e
@reboot /usr/bin/python3 /path/to/webbuttonpress.py
```

---

## Step 4: Web App

1. Copy the `GarageApp` folder to your Raspberry Pi.
2. Set a password inside `index.php`.
3. Start the PHP server:

```bash
php -S <your-device-ip>:5500
```

4. Access the app:

```
http://<your-device-ip>:5500/
```

5. Optional: Use **Add to Home Screen** on your phone to use it as an app.

---

### Auto-Start App with PM2

1. Install Node.js and PM2:

```bash
npm install pm2 -g
```

2. Edit `app.js` to set your device IP.
3. Start and save:

```bash
pm2 start app.js && pm2 save
```

4. Enable startup:

```bash
pm2 startup
```

---

## Step 5: Remote Access

* Use a **VPN** to access your local network from outside your home, or
* Use **Cloudflare Tunnels** for secure remote access.

---

## Folder Structure

```
Smart-Garage-Door/
├── webbuttonpress.py        # Python script to trigger the garage
├── GarageApp/               # Web app folder
│   ├── index.php
│   ├── app.js
│   └── ... 
├── wiring_diagram.png       # Optional wiring diagram
└── README.md
```

---

## Troubleshooting

* Make sure wires are properly soldered.
* Check GPIO pin number in Python script matches your connection.
* Ensure Flask server is running.

---

Do you want me to do that next?
