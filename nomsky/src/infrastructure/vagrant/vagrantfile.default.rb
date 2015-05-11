# @return [Hash]
def box_config_virtualbox

  bridged_interfaces  = `vboxmanage list bridgedifs`
  bridged_interface   = bridged_interfaces.lines.first[/(?<=Name:).*$/].strip!

  host_only_interfaces = `vboxmanage list hostonlyifs`
  host_only_interface  = host_only_interfaces.lines.first[/(?<=Name:).*$/].strip!

  configuration = {
      :hostOnlyInterfaceName   => bridged_interface,
      :bridgedInterfaceName    => host_only_interface
  }

  configuration

end

# @return [Hash]
def box_config_libvirt

  configuration = {
      :vm_host          => 'vm-server-one',
      :vm_storage_pool  => 'boot-scratch',
      :vm_host_username => 'manager'
  }

  configuration

end

# @return [Hash]
def box_config_project

  configuration = {
      :box                     => 'ubuntu-server-14.04',
      :hostname                => 'nomsky-dev.local',
      :machine_name            => 'nomsky-dev',

      # the function this machine will perform, equivalent to puppet node

      :machine_role            => 'dev-php',

      # path on the guest where the entire project will be mounted
      :guest_path_project      => '/srv/devel',

      # list of hashes with these keys: :guestpath, :hostpath, :create, :owner, :group, :nfs, :transient, :extra
      # example:
      # {
      #   :guestpath => "/var/www/default/code",
      #   :hostpath => "../code",
      #   :group    => "www-data"
      # }
      :additional_sync_folders   => [],

      # puppet fileserver root
      :fileserver_mount_src         => '',

      :environment             => '04-production'
  }

  configuration

end
