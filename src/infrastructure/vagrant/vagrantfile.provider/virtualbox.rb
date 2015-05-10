Vagrant.configure(VAGRANTFILE_API_VERSION) { |config|

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  config.vm.provider :virtualbox do |vb|

    # Don't boot with headless mode
    vb.gui = true

    # Hardware configuration
    # Set boot sequence
    vb.customize ['modifyvm', :id, '--boot1', 'disk']
    # Set 1gb of ram
    vb.customize ['modifyvm', :id, '--memory', '1024']

    # Display configuration
    # set video memory
    vb.customize ['modifyvm', :id, '--vram', '64']
    # enable remote display
    vb.customize ['modifyvm', :id, '--vrde', 'on']
    # enable 3d acceleration support
    vb.customize ['modifyvm', :id, '--accelerate3d', 'on']

    # Audio configuration
    vb.customize ['modifyvm', :id, '--audio', 'none']

    #Motherboard configuration
    #set the motherdboard internal clock to use utc like a real linux box
    vb.customize ['modifyvm', :id, '--rtcuseutc', 'on']

    # Network configuration

    # set up first adapter as a NAT to allow internet conectivity
    vb.customize ['modifyvm', :id, '--nic1', 'nat']
    vb.customize ['modifyvm', :id, '--cableconnected1', 'on']

    # set up second adapter as a host only, static ip adapter
    vb.customize ['modifyvm', :id, '--nic2', 'hostonly']
    vb.customize ['modifyvm', :id, '--hostonlyadapter2', $box_configuration.fetch(:hostOnlyInterfaceName)]
    vb.customize ['modifyvm', :id, '--cableconnected2', 'on']

    vb.customize ['modifyvm', :id, '--nic3', 'bridged']
    vb.customize ['modifyvm', :id, '--bridgeadapter3', $box_configuration.fetch(:bridgedInterfaceName)]
    vb.customize ['modifyvm', :id, '--cableconnected3', 'on']

    #Port forwarding on host configuration
    #http port
    #vb.forward_port 80, 8080
    #mysql port
    #vb.forward_port 3306, 8889
  end

}
