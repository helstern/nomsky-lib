
filename = ''
case VAGRANT_PROVIDER
  when 'libvirt'
    puts 'choosing libvirt as provider'
    filename = [VAGRANT_SOURCE_DIR, 'vagrantfile.provider', 'libvirt.rb'].join(File::SEPARATOR)
  when 'virtualbox'
    puts 'choosing virtualbox as provider'
    filename = [VAGRANT_SOURCE_DIR, 'vagrantfile.provider', 'virtualbox.rb'].join(File::SEPARATOR)
  else
    fail ('no provider was chosen. available providers: libvirt, virtualbox')
end

require filename
