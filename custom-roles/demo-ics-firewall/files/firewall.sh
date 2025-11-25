#!/bin/bash
# This is the firewall script for the ICS machine. This machine should only
# accept connections from the Raspberry Pi and the web server. 

# This script must be run with root privileges. When running it through an 
# Ansible playbook or role, use "become: yes" to tell Ansible to run it as root.

# IMPORTANT: this allows traffic to/from the Proxmox server so Ansible can finish 
# configuring the VM. Without these two rules, the range won't deploy properly.
# MAKE ABSOLUTELY SURE this is the vmbr1000 IP address (or whichever one Ludus uses) 
iptables -A INPUT -s 192.0.2.254 -j ACCEPT
iptables -A OUTPUT -d 192.0.2.254 -j ACCEPT

# allows traffic on localhost so we can see our own web HMI
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -i lo -j ACCEPT

# allows traffic to and from the Raspberry Pi (to connect to the physical crate)
iptables -A INPUT -s 192.168.8.12 -j ACCEPT
iptables -A OUTPUT -d 192.168.8.12 -j ACCEPT

# allows web traffic and SSH traffic to and from the web server
iptables -A INPUT -s 10.2.10.80 -p tcp -m multiport --dports 22,80,443 -j ACCEPT
iptables -A OUTPUT -d 10.2.10.80 -m conntrack --ctstate ESTABLISHED -j ACCEPT

# allow the web server to ping the ICS server
iptables -A INPUT -s 10.2.10.80 -p icmp --icmp-type echo-request -j ACCEPT
iptables -A OUTPUT -s 10.2.10.80 -p icmp --icmp-type echo-reply -j ACCEPT

# sets the firewall policy to drop anything that doesn't match a rule
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# makes the firewall rules persistent (only works for Debian-based distros)
netfilter-persistent save

