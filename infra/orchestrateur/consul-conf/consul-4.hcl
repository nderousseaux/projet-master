datacenter = "dc1"
data_dir = "/opt/consul"
bootstrap_expect = 5

node_name = "nc4"

ui_config = {
  enabled = true,
}

server = true

client_addr = "0.0.0.0"

retry_join = ["10.3.0.1", "10.3.0.2", "10.3.0.3", "10.3.0.5"]

bind_addr = "10.3.0.4"
