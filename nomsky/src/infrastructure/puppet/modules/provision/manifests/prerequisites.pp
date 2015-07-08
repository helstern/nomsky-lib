class provision::prerequisites {

  $serverModule = ['software-properties-common', 'python-software-properties']
  package { $serverModule:
    ensure => "present",
  }

}
