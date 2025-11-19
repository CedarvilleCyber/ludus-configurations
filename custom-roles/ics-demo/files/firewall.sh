#!/bin/bash
# This is the firewall script for the ICS machine. This machine should only
# accept connections from the web server. 
# note: run w/ root privileges

# sets the firewall policy to drop anything that doesn't match a rule
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# allows traffic on localhost so we can see our own web HMI
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -i lo -j ACCEPT

# allows traffic from the web server
iptables -A INPUT -s 10.2.10.80 -p tcp -m multiport --dports 22,80,443 -j ACCEPT

# allows the ICS server to reply to legitimate traffic 
# (allows outbound traffic to web server) 
iptables -A OUTPUT -m conntrack --ctstate ESTABLISHED -j ACCEPT

netfilter-persistent save
