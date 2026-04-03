# SNMP Agent Role

This role installs and configures an SNMP agent (net-snmp) in a vulnerable manner for use in cyber training networks.

**⚠️ WARNING**: Do not use this in production. This role intentionally creates security weaknesses for lab exercises.

## Overview

This role supports multiple SNMP versions (v1, v2c, and v3) with flexible configuration options for different training scenarios. It handles installation, configuration, firewall rules, and service management across multiple Linux distributions.

## Variables

### SNMP Version

- **`snmp_version`** (default: `"v2c"`): SNMP version to expose
  - Options: `"v1"`, `"v2c"`, `"v3"`

### SNMPv1/v2c Settings

- **`snmp_community_string`** (default: `"public"`): Community string for SNMPv1/v2c authentication
- **`snmp_community_source`** (default: `"default"`): Source IP/CIDR restriction for community string access
  - Use `"default"` to allow all sources
  - Examples: `"192.168.1.0/24"`, `"10.0.0.1"`

### SNMPv3 Settings

- **`snmp_v3_users`** (default: `[]`): List of SNMPv3 users

Each entry in `snmp_v3_users` supports:

| Parameter | Required | Options | Default |
|-----------|----------|---------|---------|
| `username` | Yes | Any string | — |
| `authproto` | No | `MD5`, `SHA` | `SHA` |
| `authpass` | Yes | Any string | — |
| `privproto` | No | `DES`, `AES` | `AES` |
| `privpass` | Yes | Any string | — |
| `access` | No | `ro`, `rw` | `ro` |

**Example**:
```yaml
snmp_v3_users:
  - username: labuser
    authproto: SHA
    authpass: "Auth_P@ss1"
    privproto: AES
    privpass: "Priv_P@ss1"
    access: ro
```

### OID View Settings

- **`snmp_oid_view`** (default: `"full"`): Which OID tree to expose
  - `"full"`: Entire tree (.1) — good for enumeration exercises
  - `"system"`: Only system info (.1.3.6.1.2.1.1) — minimal exposure
  - `"custom"`: Use `snmp_custom_oids` list below

- **`snmp_custom_oids`** (default: `[".1.3.6.1.2.1.1"]`): List of specific OIDs to expose
  - Used only when `snmp_oid_view: "custom"`
  - Example: `[".1.3.6.1.2.1.1", ".1.3.6.1.4.1"]`

### Network Settings

- **`snmp_agent_address`** (default: `"udp:161"`): SNMP agent binding address
  - Examples: `"udp:161"`, `"udp6:161"`, `"127.0.0.1:161"`

- **`snmp_agent_port`** (default: `161`): SNMP agent port

### System Metadata

- **`snmp_syscontact`** (default: `"Lab Admin"`): System contact (sysContact OID)
- **`snmp_syslocation`** (default: `"Lab Environment"`): System location (sysLocation OID)

### Firewall

- **`snmp_manage_firewall`** (default: `false`): Automatically open port 161/udp via firewalld/ufw
  - Set to `true` to automatically configure firewall rules

## Supported Distributions

- Debian/Ubuntu
- RedHat/Rocky/Alma/Fedora
- SUSE
- Arch Linux

## Vulnerable Features

This role intentionally includes:
- Weak/default community strings
- Full OID tree exposure (by default)
- User enumeration via `/etc/passwd` extension
- Minimal access controls

This is appropriate for security training and penetration testing exercises only. 
