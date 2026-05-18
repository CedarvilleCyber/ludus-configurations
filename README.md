# Ludus Configurations
This repository contains the various Ludus templates and custom Ansible roles 
needed to implement the Jericho senior design project. 

The goal of the Jericho senior design project is to create an internet-accessible
training environment that uses a cyber-physical city model to assist students from
high school to college in learning specific cyber tactics and fulfilling concrete
learning outcomes.

To accomplish this, we created a variety of training scenarios that utilize 
Ludus to quickly set up and tear down computer networks. You can learn more about
the Ludus project at https://ludus.cloud.

## Deploying a Range
To deploy a range, you need to determine which config is associated with that 
range. Then, read the config to determine which Ansible roles it needs. If an
Ansible role is custom, it will be located in ludus-configurations/custom-roles/.

For custom roles, read their tasks/main.yml file to determine if they're importing
or including any roles. If they are, add those imported roles to Ludus as well. 

For each Ansible role used in a config, use the following command to add
the role to Ludus: 
```
ludus ansible role add [role-name]   # for roles available in ansible-galaxy
ludus ansible role add -d /path/to/custom/role   # for custom roles

Ex: 
ludus ansible role add bertvv.vsftpd   # adds a widely-used FTP server role
ludus ansible role add -d custom-roles/web_setup   # adds our web app deployment role
```

Once all necessary Ansible roles have been added to Ludus, set the config and
deploy the range like so:  
```
ludus range config set -f [config-file.yml]
ludus range deploy # builds the range specified by the config.yml
```

## Ansible Roles
### Public Ansible Roles
If a training scenario requires a public Ansible role, you'll get an error like 
this: "Configuration error: the role [role-name] does not exist on the Ludus
server for user [username]."

To remediate this issue, run this command on your Ludus server: 
`ludus ansible role add [role-name]`
### Private Ansible Roles
To add a private Ansible role, find the role's directory in the Git repo. Then,
add it like so:
`ludus ansible role add -d ./custom-roles/add-user`
(This example adds the "add-user" role which is located in the top-level
custom-roles directory in the repo.)
### Testing Ansible Roles
If you need to modify a role, make sure to remove the role and add it back before
redeploying your range. If you don't do this, your changes will not take effect.

Commands:
`ludus ansible role rm [role-name]`
`ludus ansible role add -d ./path/to/role`

To test your changed role, do this:
```
ludus range deploy --limit [vm-name] --only-roles [role-to-test]
```
- This limits the deployment to only the specified machine and specified Ansible role.
- The other machines stay in the same state they were in after the previous deployment.
- This drastically speeds up your range deployment, which is handy for testing.

I recommend developing Ansible roles in WSL on your personal laptop. You can
install VS Code in WSL, which gives you a modern IDE combined with the benefits
of developing in a Linux environment. 

If you do this, VS Code will give you syntax help for your Ansible roles, which
is quite handy. 
## Ludus Networking
### External Default Usage
In the Networking section of a Ludus range config, there's a setting called 
`external_default`. If you set `external_default` to `REJECT`, your range will
not be able to access the internet, which is a useful feature for sandboxing. 
However, if you deploy a range with this setting enabled, your range deployment 
will fail.

To properly use this feature, you must first deploy the range with 
external\_default set to "ACCEPT." This allows the VMs in the range to download 
all the packages they need. Once range deployment is done, change 
external\_default to "REJECT" or "DROP," set the config with the ludus command,
and re-deploy the networking section of the range config like so: 
```
ludus range config set -f my-config.yml
ludus range deploy -t network
```
- This will leave the VMs intact and fully configured
- Only the range's networking will be impacted (i.e. redone)

I believe you can also use the "Testing" feature to remove internet access from
your Ludus range, which would likely be much easier. Check the docs for more information.

To learn more about deploy tags, see the official docs here: 
https://docs.ludus.cloud/docs/tags
## Troubleshooting
If you encounter trouble deploying a range, use these commands to troubleshoot:
`ludus range logs --verbose`
`ludus range errors --verbose`

When using the verbose flag to view Ludus errors, Ludus prints newlines as the 
literal "\n" characters instead of printing as a newline. That's one of Ludus's
design choices. To work around that, do this: 
```
ludus range errors --verbose 2>&1 | sed 's/\\n/\n/g' | less
```

