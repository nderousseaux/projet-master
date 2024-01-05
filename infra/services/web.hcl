job "web" {

  group "web" {
    count = 2

    network {
      port "web" {
        to = 80
      }
    }

    task "web" {
      driver = "docker"
      config {
        force_pull = true
        image = "registry.app.unistra.fr/fseel/projet-master-23-24/web"
        ports = ["web"]
      }

      env {
        PORT    = "${NOMAD_PORT_web}"
        NODE_IP = "${NOMAD_IP_web}"
      }

			service {
				name = "web"
				port = "web"

				check {
					name     = "alive"
					type     = "http"
					protocol = "http"
					path     = "/"
					interval = "10s"
					timeout  = "2s"
				}
			}

      resources {
        cpu    = 200
        memory = 256
      }
    }

    scaling {
      enabled = true
      min = 2
      max = 10
    }
	}
}

