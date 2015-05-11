class provision::main {
  include apt

# configure some common low level settings
  include setup_system

  include apache
#  include setup_java
  include setup_mysql

  include php
  include php::phpmyadmin
  include php::phpcomposer

#include setup_javascript
}
