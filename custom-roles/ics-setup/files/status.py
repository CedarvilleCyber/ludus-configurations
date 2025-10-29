#!/usr/bin/python3

# Server for the pi to check scenario status

# The purpose of this script is to allow you to use new scenarios with different
# protocols on the same hardware without needing to change anything on the Pi Zeroes.
# This script allows the script on the Pi Zeroes to be protocol-agnostic.

import socket

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

host = '0.0.0.0'
port = 9999

s.bind((host, port))
s.listen(5)

try:
    while True:
        c, addr = s.accept()
        print(f"Connection from {addr}")
  
        f = open("status.txt", "rb")
        status = f.read()
        f.close()

        c.send(status)

        # reset status.txt no matter what
        f = open("status.txt", "w")
        status = f.write("11")
        f.close()
except:
    s.shutdown(socket.SHUT_RDWR)
    s.close()