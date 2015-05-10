Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.network :public_network, :mode => 'bridge', :type => 'bridge', :dev => 'br0'

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider :libvirt do |libvirt|

    # connection information

    libvirt.driver          = 'kvm'
    libvirt.host            = $box_configuration.fetch(:vm_host)
    libvirt.username        = $box_configuration.fetch(:vm_host_username)
    libvirt.connect_via_ssh = true

    # connection independent options

    libvirt.storage_pool_name = $box_configuration.fetch(:vm_storage_pool)

    # domain specific options

    libvirt.memory = 512
    libvirt.cpus = 1

 end

end
