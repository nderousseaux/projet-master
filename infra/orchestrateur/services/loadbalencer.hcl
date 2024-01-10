job "haproxy" {
  datacenters = ["dc1"]
  type        = "service"

  group "haproxy" {
    count = 1

    # Il sera disponible depuis 8081
    network {
      port "http" {
        static = 80
      }
      port "stats" {
        static = 8080
      }
    }

    service {
      name = "haproxy"

      check {
        name     = "alive"
        type     = "tcp"
        port     = "http"
        interval = "10s"
        timeout  = "2s"
      }
    }

    task "haproxy" {
      driver = "docker"

      config {
        image        = "haproxy:2.0"
        network_mode = "host"

        volumes = [
          "local/haproxy.cfg:/usr/local/etc/haproxy/haproxy.cfg",
        ]
      }

      template {
        data = <<EOF
defaults
   mode http
   maxconn 10000

frontend stats
  bind *:8080
   stats show-legends
   no log

backend front_back
  http-response set-header Access-Control-Allow-Origin "*"
  balance roundrobin
  server-template web 1-10 _web._tcp.service.consul resolvers consul resolve-opts allow-dup-ip resolve-prefer ipv4 check

frontend http-in
  bind *:80
  mode http
  default_backend front_back



resolvers consul
    nameserver consul 10.3.0.1:8600
    accepted_payload_size 8192
    hold valid 5s
EOF

        destination = "local/haproxy.cfg"
      }

      resources {
        cpu    = 200
        memory = 256
      }
    }
  }
}