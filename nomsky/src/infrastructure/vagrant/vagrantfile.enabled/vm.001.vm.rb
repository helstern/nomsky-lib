
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = $box_configuration.fetch(:box)

  # Set the hostname of the machine
  config.vm.hostname = $box_configuration.fetch(:hostname)

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing 'localhost:8080' will access port 80 on the guest machine.
  # config.vm.network :forwarded_port, guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network :private_network, ip: '192.168.33.10', auto_config: true, type: "dhcp"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network :public_network, type: "dhcp", auto_config: false

  # Share the project folder
  config.vm.synced_folder PROJECT_HOST_DIR, $box_configuration.fetch(:guest_path_project), type: 'nfs'

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder '../data', '/vagrant_data'
  $box_configuration.fetch(:additional_sync_folders).each do |opts|
    config.vm.synced_folder opts[:hostpath], opts[:guestpath], opts.reject{|key,value| [:hostpath, :guestpath].include?(key)}
  end

  # Configure ssh access
  config.ssh.forward_agent = true
  config.ssh.forward_x11 = true
end
