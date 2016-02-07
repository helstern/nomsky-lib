
filename = ''
case VAGRANT_PROVIDER
  when 'libvirt'
    filename = [VAGRANT_SOURCE_DIR, 'vagrantfile.provider', 'libvirt.rb'].join(File::SEPARATOR)
  when 'virtualbox'
    filename = [VAGRANT_SOURCE_DIR, 'vagrantfile.provider', 'virtualbox.rb'].join(File::SEPARATOR)
  else
    fail ('no provider was chosen. available providers: libvirt, virtualbox')
end

require filename
