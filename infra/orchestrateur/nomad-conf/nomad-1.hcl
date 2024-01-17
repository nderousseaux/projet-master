datacenter = "dc1"
name = "nc1"
data_dir  = "/opt/nomad/data"
bind_addr = "0.0.0.0"

advertise {
  http = "10.3.0.1"
  rpc = "10.3.0.1"
  serf = "10.3.0.1"
}

server {
  enabled          = true
  bootstrap_expect = 5
}

client {
  enabled = true
  servers = ["10.3.0.1", "10.3.0.2", "10.3.0.3", "10.3.0.4", "10.3.0.5"]
  network_interface = "vens4"
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