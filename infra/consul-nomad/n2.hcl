datacenter = "dc1"
name = "nc2"
data_dir  = "/opt/nomad/data"
bind_addr = "0.0.0.0"

advertise {
  http = "{{ GetInterfaceIP `enp0s3` }}"
  rpc = "{{ GetInterfaceIP `enp0s3` }}"
  serf = "{{ GetInterfaceIP `enp0s3` }}"
}

server {
  enabled          = true
  bootstrap_expect = 2
}

client {
  enabled = true
  servers = ["192.168.56.5, 192.168.56.6"]
  network_interface = "enp0s3"
}
# Authentification docker
plugin "docker" {
  config {
    auth {
      config = "/root/.docker/config.json"
    }
  }
}