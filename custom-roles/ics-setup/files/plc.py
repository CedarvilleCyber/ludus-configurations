#!/usr/bin/python3
# Water treatment Siemens PLC
# Runs on the PLC container. status.py must be running on the PLC container as
# well for the scenario to work properly.

# for python-snap7 Version: 2.0.2

import threading
from time import sleep
from snap7 import server
import snap7
from snap7.type import SrvArea, WordLen, CDataArrayType

# ab - input parameter, readings from sensors
# db - data block, meant for storing misc data
# mb - a single byte of data storage
# eb - output parameter, meant to send info to actuators


# mainloop code copied from library
# modified to add listening IP/interface so we can run multiple on one machine
def mainloop(tcp_port: int = 102, ip: str = '0.0.0.0'):
    """Init a fake Snap7 server with some default values.

    Args:
        tcp_port: port that the server will listen.
        ip: ip address to listen on
    """

    server = snap7.server.Server()
    size = 100
    db_data: CDataArrayType = (WordLen.Byte.ctype * size)()
    pa_data: CDataArrayType = (WordLen.Byte.ctype * size)()
    tm_data: CDataArrayType = (WordLen.Byte.ctype * size)()
    ct_data: CDataArrayType = (WordLen.Byte.ctype * size)()
    server.register_area(SrvArea.DB, 1, db_data)
    server.register_area(SrvArea.PA, 1, pa_data)
    server.register_area(SrvArea.TM, 1, tm_data)
    server.register_area(SrvArea.CT, 1, ct_data)

    server.start_to(tcp_port=tcp_port, ip=ip)
    while True:
        while True:
            event = server.pick_event()
            if not event:
                break
        sleep(1)


def update():
    client = snap7.client.Client()
    client.connect('127.0.0.1', 0, 0)

    data = b'\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00'
    client.ab_write(0, data)

    while True:
        data = b'\x01\x01'
        client.ab_write(0, data)
        sleep(1)


def display(ip: str = '127.0.0.1'):
    client = snap7.client.Client()
    client.connect(ip, 0, 0)

    while True:
        print(client.ab_read(0, 16))
        sleep(1)


if __name__ == "__main__":
    server_thread = threading.Thread(target=mainloop, name="Server", args=(102, '0.0.0.0'))
    server_thread.start()
    sleep(1)

    display_thread = threading.Thread(target=update, name="Update")
    display_thread.start()
    sleep(1)

    display_thread = threading.Thread(target=display, name="Display", args=('127.0.0.1',))
    display_thread.start()
    sleep(1)

"""
Storage order

clarifier 1 action - 1 byte
clarifier 2 action - 1 byte

Action
1 = on
0 = off
"""
