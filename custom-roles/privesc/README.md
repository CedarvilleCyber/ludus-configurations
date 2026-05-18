# Ansible Role: Privilege Escalation Binary Setup ([Ludus](https://ludus.cloud))

An Ansible Role that configures a binary for privilege escalation in cyber training scenarios. This role verifies the binary exists on the system, installs it if necessary, and sets the SUID bit to enable privilege escalation.

## Requirements

None.

## Role Variables

Available variables are listed below, along with default values (see `defaults/main.yml`):

    # The binary name to configure for privilege escalation
    ludus_privesc_binary: ""

Set `ludus_privesc_binary` to the name of the binary you want to configure (e.g., `sudo`, `passwd`, `cp`, etc.).

## Dependencies

None.

## Example Playbook

```yaml
- hosts: linux_hosts
  roles:
    - privesc
  vars:
    ludus_privesc_binary: "cp"
```
## How It Works

1. Verifies the binary exists on the system
2. Attempts to install the binary via package manager if not found
3. Sets the SUID (Set User ID) bit on the binary, allowing it to execute with owner privileges

## License

[//]: # (If you change the License type, be sure to change the actual LICENSE file as well)
GPLv3

## Author Information

This role was created by David-B-Moore for [Ludus](https://ludus.cloud/).
