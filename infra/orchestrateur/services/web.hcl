job "web" {
  datacenters = ["dc1"]

  group "web" {
    count = 5

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

      template {
	data = "MONGODB_URL=\"mongodb://{{ $services := service \"mongo\" }}{{ range $index, $service := $services }}{{ .Address }}:{{ .Port }}{{ if ne (add $index 1) (len $services) }},{{ end }}{{ end }}\""
	destination = "/var/mongo.env"
	env = true
      }
      
			service {
				name = "web"
				tags = ["urlprefix-/"]
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
