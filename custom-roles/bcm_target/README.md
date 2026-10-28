# bcm_target

Provisions the **BCM scenario target machine** — the single shared box students
reach after finding the foothold credentials on their Kali.

What it does:

1. Creates the login user (`signal_op`) and the `admin` user with set passwords.
2. Drops `flag.txt` (foothold flag) in the login user's home.
3. Plants a hidden `ls -a` file holding the admin credentials.
4. Installs `rush_hour.sh` in the admin user's home, **non-executable**, so
   students practice `chmod +x`. Running it triggers the traffic effect and
   prints the final flag; its last line resets its own permissions.
5. Adds an `/etc/hosts` entry so `traffic.jericho.local` resolves to Master
   Control, and enables SSH password login.
6. Blocks password changes on the shared accounts.

All values live in `defaults/main.yml` and can be overridden from `role_vars`
in the range config.

## Cross-dependencies (outside this role)
- The Kali's `/opt/trafficgrid/pass.txt` must contain **`bcm_login_password`**
  (that's the password students use to SSH here).
- Students need this target's **IP address** to SSH to it — put it in the Kali's
  `README.txt` or on the scenario page.

## Example role_vars
```yaml
role_vars:
  bcm_target:
    bcm_login_password: "St4ll_Th3_M0t0rc4d3!"
    bcm_final_flag: "RUSH{sv3tl0v_st4ll3d_1n_tr4ff1c}"
```
