datacenter = "dc1"
data_dir = "/opt/consul"
bootstrap_expect = 2

node_name = "nc1"

ui_config = {
  enabled = true,
}

server = true

client_addr = "0.0.0.0"

retry_join = ["192.168.56.6"]

bind_addr = "192.168.56.5"
