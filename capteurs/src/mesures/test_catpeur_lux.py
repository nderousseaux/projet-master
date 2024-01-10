import time
import board
import adafruit_veml7700

i2c = board.I2C()
veml = adafruit_veml7700.VEML7700(i2c)

while True:
    print("lux = ", veml.light)
    time.sleep(0.2)

