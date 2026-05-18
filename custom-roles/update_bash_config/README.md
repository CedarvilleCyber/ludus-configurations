# README

This role was created to add arbitrary commands to a user's `.bashrc` or 
`.bash_profile` for Ludus configs. In a standard Ansible playbook you wouldn't
write an Ansible role for this, but it's convenient to have a role for it when
working with Ludus. 
## Usage
This is how you would use the role in a Ludus config: 
```
  - vm_name: "{{ range_id }}-ot-server"
    hostname: "ot-server"
    template: "debian-12-x64-server-template"
    vlan: 11
    ip_last_octet: 43
    ram_gb: 4
    cpus: 2
    linux:
      packages:
        - tmux
        - python3
        - python3-rich
        - python3-requests
    roles:
      - ludus_local_users
      - file-transfer
      - update_bash_config
    role_vars:
      ludus_local_users:
        - login: "mjackson"
          password: "dragon"
          sudo_nopasswd: true
      file_transfer_src: "https://raw.githubusercontent.com/CedarvilleCyber/Jericho/refs/heads/feature/nuclear-tui/control-panels/nuclear-effect/nuclear-tui.py"
      file_transfer_dst: "/home/mjackson/nuclear-control.py"
      update_bash_config:
        file: ".bash_profile"
        user: "mjackson"
        block: |
            tmux new-session
            tmux rename-window -t 0 "Control"
            tmux send-keys -t "Control" "python3 /home/mjackson/nuclear-control.py" C-m
```

In the above example, `mjackson`'s `.bash_profile` is being modified to run a 
nuclear control panel program in the first tmux window when `mjackson` logs in. 

If this code was added to `mjackson`'s `.bashrc`, this would happen each time
`mjackson` opened a new tmux window, which would be irritating. However, since
the code was added to `.bash_profile` instead, it only happens once, when 
`mjackson` initially logs in.

## License

GPLv3

## Author Information

This role was created by [David-B-Moore](https://github.com/David-B-Moore), for [Ludus](https://ludus.cloud/).
