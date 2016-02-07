class provision {

  include provision::prerequisites

  class { 'provision::main' :
    require => Class['provision::prerequisites']
  }
}
