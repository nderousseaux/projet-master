datacenter = "dc1"
name = "nc2"
data_dir  = "/opt/nomad/data"
bind_addr = "0.0.0.0"

advertise {
  http = "{{ GetInterfaceIP `vxlan100` }}"
  rpc = "{{ GetInterfaceIP `vxlan100` }}"
  serf = "{{ GetInterfaceIP `vxlan100` }}"
}

server {
  enabled          = true
  bootstrap_expect = 5
}

client {
  enabled = true
  network_interface = "vens4"
  host_network "internal"{
    interface = "vens4"
    cidr = "10.3.0.2/16"
  }
  host_network "public"{
    interface = "vens4"
    cidr = "10.3.0.6/16"
  }
}
# Authentification docker
plugin "docker" {
  config {
    auth {
      config = "/root/.docker/config.json"
    }
    volumes {
      enabled = true
    }
  }
}