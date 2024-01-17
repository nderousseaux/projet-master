datacenter = "dc1"
name = "nc3"
data_dir  = "/opt/nomad/data"
bind_addr = "0.0.0.0"

advertise {
  http = "10.3.0.3"
  rpc = "10.3.0.3"
  serf = "10.3.0.3"
}

server {
  enabled          = true
  bootstrap_expect = 5
}

client {
  enabled = true
  servers = ["10.3.0.1", "10.3.0.2", "10.3.0.3", "10.3.0.4", "10.3.0.5"]
  network_interface = "vens4"
  host_network "internal"{
    interface = "vens4"
    cidr = "10.3.0.3/16"
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