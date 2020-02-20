# Copy below this point

# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|

  config.vm.box = "levelten/ubuntu64-php5.6"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.10"

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
   config.vm.provision "shell", inline: <<-SHELL
     apt-get update
     apt-get install -y unzip

     apt-get install -y sqlite3

     # install pdo sqlite driver
     apt-get install -y php5.6-sqlite

     # Get java for liquibase
     apt-get install -y openjdk-8-jre-headless

     printf "${GREEN}Start Provisioning Liquibase${NC}\n"

     # Get liquibase
     mkdir liquibase
     cd liquibase
     wget https://github.com/liquibase/liquibase/releases/download/v3.8.5/liquibase-3.8.5.tar.gz
     tar -zxvf liquibase-3.8.5.tar.gz
     rm liquibase-3.8.5.tar.gz
     echo 'export PATH="${PATH}:/home/vagrant/liquibase"' >> /home/vagrant/.bashrc
     source /home/vagrant/.bashrc

     printf "${GREEN}Start Provisioning Database Structure and fields.${NC}\n"

     # Create a database
     # mysql -u root -e "create database if not exists zftest;"
     # mysql -u root -e "use zftest; CREATE TABLE IF NOT EXISTS users_acess( id int(11) not null auto_increment, name varchar(50) not null, permition_level int(2) not null, Primary Key (id));INSERT INTO users_access(name, permition_level) values('default', 0), ('admin', 1), ('master', 2);"
     # mysql -u root -e "use zftest; CREATE TABLE IF NOT EXISTS users( id int(11) not null auto_increment, name varchar(50) not null, username varchar(100) not null unique, password varchar(255) not null, access_level INT(11) DEFAULT 0,active TINYINT(1) DEFAULT 0, LOCKED TINYINT(1) DEFAULT 0, Primary Key (id), FOREIGN KEY (access_level) REFERENCES users_access(id));"
     
     cd /home/vagrant

     touch /vagrant/zftest.sqlite

     sqlite3 /vagrant/zftest.sqlite "attach database 'zftest.sqlite' as zftest;"
     sqlite3 /vagrant/zftest.sqlite "CREATE TABLE IF NOT EXISTS users_access( id integer primary key, name varchar(50) not null, permition_level int(2) not null);INSERT OR IGNORE INTO users_access(id, name, permition_level) values(1, 'default', 0), (2, 'admin', 1), (3, 'master', 2);"
     sqlite3 /vagrant/zftest.sqlite "CREATE TABLE IF NOT EXISTS users( id integer primary key, name varchar(50) not null, username varchar(100) not null unique, password varchar(255) not null, access_level INT(11) DEFAULT 0,active TINYINT(1) DEFAULT 0, locked TINYINT(1) DEFAULT 0, FOREIGN KEY (access_level) REFERENCES users_access(id));"
     sqlite3 /vagrant/zftest.sqlite ".exit"

   SHELL
end

# End here