 Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  with_hiera = nil
  if !PUPPET_PROJECT_HIERA.nil?
      with_hiera = [];
      Dir.foreach(PUPPET_PROJECT_HIERA) do |filename|
        file_path = File.join(PUPPET_PROJECT_HIERA, filename)
        next unless File.directory?( file_path )
        next if %w(. ..).include?(filename)

        with_hiera.push(:host_path => file_path, :name => filename)
      end
  end

  guest_path_project = $box_configuration.fetch(:guest_path_project)
  guest_path_puppet_modules = [guest_path_project, 'src', 'provision', 'puppet', 'modules'].join('/')
  configurator = PuppetStandaloneConfigurator.new(
      :provisioner_id => 'puppet',
      :guest_path => '/puppet',
      :with_hiera => with_hiera,
      :puppet => {
        :use_default_manifests => true,
        :module_extra_dirs => [guest_path_puppet_modules]
      }
  )

  configurator.sync_folders(config.vm)
  configurator.install(config.vm)

  configurator.configure config.vm do |puppet|

    puppet.manifests_path = "#{PUPPET_PROJECT_MANIFESTS_PATH}"
    puppet.manifest_file = 'bootstrap.pp'

    environment = $box_configuration.fetch(:environment)
    machine_role = $box_configuration.fetch(:machine_role)

    # facter
    puppet.facter = {
        :environment      => environment,
        :machine_role     => machine_role,
        :location         => 'home'
    }

    # set puppet options
    puppet.options << "--node_name_value=#{machine_role}"
    puppet.options << '--verbose'
    puppet.options << '--ordering=manifest'

    unless $box_configuration.fetch(:fileserver_mount_src).to_s.empty?
      puppet.options << '--fileserverconfig /puppet/puppet-fileserver/conf/fileserver.conf'
    end

  end

end
