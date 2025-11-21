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
There should be a deploy-range.sh script that sets the Ludus range config, adds
the Ansible roles, and deploys the range. This can all be done manually, as well.

If you want to set up the range manually, do these steps in order:
```
ludus range config set -f [config-file.yml]
ludus ansible role add -d /path/to/your/role # do this for every Ansible role
ludus range deploy # builds the range specified by the config.yml

# These next steps use an Ansible playbook to remove default creds from the VMs 
# in the range. The playbook also creates a user with the following creds:
# debug:debug-user-4-ansible-sysadmin
# Note: using these creds to get into a machine in an assigned scenario is 
# considered academic dishonesty and will be treated as such.
ludus range inventory > ludus-inventory.yml # do after range finishes building
ansible-playbook rm-default-creds-playbook.yml -i ludus-inventory.yml
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
### Troubleshooting
If you encounter trouble deploying a range, use these commands to troubleshoot:
`ludus range logs --verbose`
`ludus range errors --verbose`

At the time of writing, Ludus has a bug where newlines are printed as the literal
"\n" characters instead of printing as a newline. To work around that, do this: 
`ludus range errors --verbose 2>&1 | sed 's/\\n/\n/g' | less`

If you need to modify a role, make sure to remove the role and add it back before
redeploying your range. If you don't do this, your changes will not take effect.

Commands:
`ludus ansible role rm [role-name]`
`ludus ansible role add -d ./path/to/role`

To test your changed role, do this:
`ludus range deploy --limit [vm-name] --only-roles [role-to-test]`

