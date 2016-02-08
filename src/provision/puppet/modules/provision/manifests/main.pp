class provision::main {
  include apt

  include setup_system

  include php
  include php::phpcomposer
}
